<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user){
            $dateCreated = $user->getCreatedAt();
            $now = new DateTime();
            $diffTime = $user->getCreatedAt()->diff($now);
            if (!$user->isVerified() && $diffTime->h >= 1){
                $url = $urlGenerator->generate('app_mail_resend');
                $this->addFlash('warning', "Votre compte n'a pas était vérifier. <a href='$url'>Recevoir de nouveau l'email de confirmation</a>");
            }
        }
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
