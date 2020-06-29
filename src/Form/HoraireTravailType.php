<?php

namespace App\Form;

use App\Entity\HoraireTravail;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class HoraireTravailType extends AbstractType
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
            'choice_label' => 'user.username' ,
            'attr' => ['class' => 'form-control']
        ])
        ->add('jours', ChoiceType::class, [
            'choices'  => [
                'Lundi' => 'Lundi',
                'Mardi' => 'Mardi',
                'Mercredi' => 'Mercredi',
                'Jeudi' => 'Jeudi',
                'Vendredi' => 'Vendredi',
                'Samedi' => 'Samedi',
                'Dimanche' => 'Dimanche'
            ],
            'attr' => ['class' => 'form-control']      
            ])
            ->add('heureDebutMatin', TextType::class,
               ['attr' => ['class' => 'form-control'],
               'required' => false
            ])
            ->add('heureFinMatin', TextType::class,
               ['attr' => ['class' => 'form-control'],
               'required' => false
            ])
            ->add('heureDebutAp', TextType::class,
               ['attr' => ['class' => 'form-control'],
               'required' => false
            ])
            ->add('heureFinAp', TextType::class,
            ['attr' => ['class' => 'form-control'],
            'required' => false
         ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HoraireTravail::class,
        ]);
    }
}
