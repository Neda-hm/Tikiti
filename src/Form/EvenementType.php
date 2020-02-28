<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;



class EvenementType extends AbstractType
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
            'choice_label' => 'user.username'
        ])
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateDebutTemp', HiddenType::class )
            ->add('dateFinTemp', HiddenType::class)
            ->add('heureDebut', HiddenType::class )
            ->add('heureFin', HiddenType::class )
        ;

    }
   
public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
} 