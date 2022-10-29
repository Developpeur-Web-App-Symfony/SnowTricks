<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\Authenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        Authenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles($user->getRoles());
            $user->setCreatedAt(new \DateTime('now'));

            $entityManager->persist($user);
            $entityManager->flush();

            // On génère le JWT de l'utilisateur
            // On crée le Header
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];

            // On crée le Payload
            $payload = ['user_id' => $user->getId()];

            $validity = 3600;

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('secret_key'), $validity);

            // generate a signed url and email it to the user
            $mail->send('no-reply@snowtricks.com',
                $user->getEmail(),
                'Activation de votre compte sur le SnowTricks',
                'confirmationSignInEmail',
                compact('user', 'token')
            );
            $this->addFlash('success', "Veuillez verifier votre boîte mail pour valider votre compte");

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', ['registrationForm' => $form->createView(),]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser(
        $token,
        JWTService $jwt,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ): Response
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('secret_key'))){
            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du token
            $user = $userRepository->find($payload['user_id']);

            //On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->isVerified() === true){
                $user->setIsVerified(true);
                $user->addRole(SecurityController::ROLES_USER_VERIFIED);
                $user->setRoles($user->getRoles());
                $createToken =new UsernamePasswordToken($user,'main', $user->getRoles());
                $tokenStorage->setToken($createToken);
                $em->flush();
                $this->addFlash('success', 'Votre compte est activé');
                return $this->redirectToRoute('app_home');
            }
        }
        // Ici un problème se pose dans le token
        $this->addFlash('errors', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/resendMailTokenValidation', name: 'app_mail_resend')]
    public function resendConfirmationMail(SendMailService $mail, JWTService $jwt): RedirectResponse
    {
        if (!$this->getUser()){
            $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];

        // On crée le Payload
        $payload = ['user_id' => $user->getId()];

        $validity = 3600;

        // On génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('secret_key'), $validity);

        // generate a signed url and email it to the user
        $mail->send('no-reply@snowtricks.com',
            $user->getEmail(),
            'Activation de votre compte sur le SnowTricks',
            'confirmationSignInEmail',
            compact('user', 'token')
        );
        $this->addFlash('success', "Un email de confirmation de votre compte vient de vous être renvoyé");
        return $this->redirectToRoute('app_login');
    }
}
