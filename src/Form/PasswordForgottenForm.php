<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordForgottenForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'placeholder' => "Votre nom d'utilisateur ..."
                ],
                'constraints' => [
                    new Length(['min' => 3, 'minMessage' => "Votre nom d'utilisateur nécessite au minimum {{ limit }} caractères"]),
                    new NotBlank(),
                ],
            ])
        ;
    }
}
