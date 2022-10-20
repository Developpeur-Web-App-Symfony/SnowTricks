<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordForgottenForm;
use App\Form\ResetPasswordForm;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ForgottenPasswordController extends AbstractController
{

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/mot-de-passe-oublier', name: 'app_forgotten_password')]
    public function forgottenPassword(Request $request, SendMailService $mail, JWTService $jwt, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(PasswordForgottenForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $userBdd = $userRepository->findOneByEmail($email);

            if ($userBdd === null){
                $this->addFlash('warning', "Email non reconnu, veuillez ressayé.");
                return $this->redirectToRoute('app_forgotten_password');
            }
            // On génère le JWT de l'utilisateur
            // On crée le Header
            $header = ['typ' => 'JWT', 'alg' => 'HS256'];

            // On crée le Payload
            $payload = ['user_id' => $userBdd->getId()];

            $validity = 3600;

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('secret_key'), $validity);

            // generate a signed url and email it to the user
            $mail->send('no-reply@snowtricks.com',
                $user->getEmail(),
                'Reinitialisation de votre mot de passe',
                'resetPasswordEmail',
                compact('userBdd', 'token')
            );
            $this->addFlash('success', "Veuillez verifier votre boîte mail pour modifié votre mot de passe");
        }

        return $this->render('password/forgottenPassword.html.twig', ['passwordForgottenForm' => $form->createView(),]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/reinitialisation-mot-de-passe/{email}/{token}', name: 'reset_user_password')]
    public function resetUserPassword(
        Request $request,
        $token,
        $email,
        JWTService $jwt,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    )
    {
        //On vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('secret_key'))){
            $user = $userRepository->findOneByEmail($email);
            $form = $this->createForm(ResetPasswordForm::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager->flush($user);
                $this->addFlash('success', 'Votre mot de passe à été modifié');
                return $this->redirectToRoute('app_home');
            }
            return $this->render('password/resetPassword.html.twig', ['resetPasswordForm' => $form->createView(),]);
        }
        // Ici un problème se pose dans le token
        $this->addFlash('errors', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');


    }
}
