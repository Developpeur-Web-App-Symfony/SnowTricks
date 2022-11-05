<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    #[Route('/trick/{slug}', name: 'app_trick')]
    public function trickDetail(Trick $trick): Response
    {
        return $this->render('trick/trickDetail.html.twig', [
            'trick' => $trick,
        ]);
    }
}
