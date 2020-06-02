<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Entreprise;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class Evenement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('dateDebutTemp', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('dateFinTemp', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('heureDebut', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control',
                    
                ]
            ])
            ->add('heureFin', HiddenType::class, [
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
