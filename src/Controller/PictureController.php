<?php

namespace App\Controller;


use App\Entity\Picture;
use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{

    const KEY_DIRECTORY_TRICK = "images_directory";

    public function uploadPicture($form, $fieldName, $object): void
    {
        $picture = $form->get($fieldName)->getData();

        $this->managementPicture($picture, $object);
    }

    public function managementPicture($pictures, $object): void
    {
        if ($pictures != null) {
            switch (get_class($object)) {
                case Trick::class:
                    $file = $this->generateUniqueName($pictures);
                    $this->movePicture($pictures, $file, self::KEY_DIRECTORY_TRICK);
                    $object->setAdditionalImage($file);
                    $object->setUpdatedAt(new \DateTime());
                    break;
                default:
                case Picture::class:
                    $file = $this->generateUniqueName($pictures);
                    $this->movePicture($pictures, $file, self::KEY_DIRECTORY_TRICK);
                    $object->setFilename($file);
                    break;
            }
        }

    }

    private function movePicture($picture, string $file, string $keyParameter): void
    {
        $picture->move(
            $this->getParameter($keyParameter),
            $file
        );
    }

    private function generateUniqueName($picture): string
    {
        return md5(uniqid()) . '.' . $picture->guessExtension();
    }
}
