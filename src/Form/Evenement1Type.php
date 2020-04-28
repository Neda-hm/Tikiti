<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Entreprise;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class Evenement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('entreprise', EntityType::class, [
            'class' => Entreprise::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                ->leftJoin('e.user', 'u')
                ->addSelect('u')
                ->orderBy('u.username', 'ASC');
            },
            'choice_label' => 'user.username',
            'attr' => ['class' => 'form-control']

            
        ])
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('dateFin', DateType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('heureDebut',TimeType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('heureFin', TimeType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
