<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('prenom', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('email', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('username', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('tel', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('password', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('adresse', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('ville', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('codePostale', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('lat', HiddenType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('lng', HiddenType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
