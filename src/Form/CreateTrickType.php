<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class CreateTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Titre de la figure",
                'required' => true
            ])
            ->add('description')
            ->add('additionalImage', FileType::class, [
                'label' => 'Uploader une image à la une :',
                'required' => false,
            ])
            ->add('altImage', TextType::class, [
                'label' => "Bref description de l'image :",
                'required' => false,
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureType::class,
                'label' => "Image",
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
            ])
            ->add('movies', CollectionType::class, [
                'entry_type' => MovieType::class,
                'label' => "Vidéo",
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('groupTrick', EntityType::class, [
                'class' => Group::class,
                'required' => true,
                'choice_label' => 'name',
                'label' => 'Choisissez un groupe :',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
