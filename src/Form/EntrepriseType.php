<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Evenement;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


use App\Form\UserType;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('user', UserType::class)

        ->add('logo',FileType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])

        ->add('categorie',EntityType::class, [
            'class' => Categories::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

                
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);

           }

}
