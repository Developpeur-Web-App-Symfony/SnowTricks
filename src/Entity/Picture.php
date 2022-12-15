<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string',length: 255,unique: true,nullable: false)]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg','image/jpg','image/png'],
        maxSizeMessage: 'Taille du fichier limité à 2M',
        mimeTypesMessage: 'Veuillez selectionner un format valide (jpeg, jpg, png)'
    )]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    private ?string $alt = "figure";

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

}
