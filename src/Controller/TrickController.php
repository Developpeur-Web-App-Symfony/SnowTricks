<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Form\CreateTrickType;
use App\Form\MovieType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/new', name: 'app_trick_new')]
    #[IsGranted('ROLE_USER')]
    public function trickNew(Request $request, SluggerInterface $slugger, PictureController $pictureController): Response
    {
        $trick = new Trick();

        $form = $this->createForm(CreateTrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $trick->setUser($this->getUser());
            $trick->setCreatedAt(new \DateTime());
            $trick->setUpdatedAt(new \DateTime());
            $trick->setSlug($slugger->slug($trick->getName()));
            if ($trick->getAltImage() === null){
                $trick->setAltImage(Trick::ALT_DEFAULT);
            }

            $pictureController->uploadPicture($form,'additionalImage',$trick);

            $pictures = $form->get('pictures');

            foreach ($pictures as $picture)
            {
                $pictureEntity = new Picture();
                //Ajout de la trick a l'image
                $pictureEntity->setTrick($trick);
                //Modification du nom de l'image pour eviter les doublons en bdd
                $pictureController->uploadPicture($picture,'filename',$pictureEntity);
                // On persiste l'entit?? Image une fois bien remplie dans la BDD
                $this->entityManager->persist($pictureEntity);
                $trick->addPicture($pictureEntity);
            }

            foreach($trick->getMovies() as $video)
            {
                $video->setTrick($trick);
                $this->entityManager->persist($video);
            }


            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            $this->addFlash("success","La figure ?? bien ??t?? cr??er");
            return $this->redirectToRoute('app_home');
        }


        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'createTrickForm' => $form->createView(),
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_trick_delete')]
    #[IsGranted('ROLE_USER')]
    public function deleteTrick(Request $request, Trick $trick): Response
    {
        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        $this->addFlash("success","La figure ?? bien ??t?? supprimer");
        return $this->redirectToRoute('app_home');
    }

    #[Route('/trick/{slug}', name: 'app_trick')]
    public function trickDetail(Trick $trick): Response
    {
        return $this->render('trick/trickDetail.html.twig', [
            'trick' => $trick,
        ]);
    }

}
