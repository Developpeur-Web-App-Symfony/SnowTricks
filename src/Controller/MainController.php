<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\User;
use App\Repository\TrickRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, UrlGeneratorInterface $urlGenerator, TrickRepository $trickRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user){
            $now = new DateTime();
            $diffTime = $user->getCreatedAt()->diff($now);
            if (!$user->isVerified() && $diffTime->h >= 1){
                $url = $urlGenerator->generate('app_mail_resend');
                $this->addFlash('warning', "Votre compte n'a pas était vérifier. <a href='$url'>Recevoir de nouveau l'email de confirmation</a>");
            }
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'tricks' => $trickRepository->findTricksLastUpdated(),
        ]);
    }

    #[Route('/trick/loadmore', name:'trick_loadmore', methods: 'POST' )]
    public function loadMoreTricks(Request $request, TrickRepository $trickRepository): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $data = [];
            $tricks = $trickRepository->findLoadMoreTricks($request->request->get('offset'), $trickRepository->count([]));
            foreach ($tricks as $trick) {
                $data[] = [
                    'id' => $trick->getId(),
                    'additionalImage' => $trick->getAdditionalImage(),
                    'altImage' => $trick->getAltImage(),
                    'name' => $trick->getName(),
                    'slug' => $trick->getSlug()
                ];
            }
            return new JsonResponse($data);
        }
    }
}
