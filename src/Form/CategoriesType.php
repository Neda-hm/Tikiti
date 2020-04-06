<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;



class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('categories', ChoiceType::class, [
                    
            'choices'  => [
                'Services-Santés' => 'Services-Santés',
                'Services-Finances' => 'Services-Finances',
                'Services-Rapides' => 'Services-Rapides',
                'Services-Publics' => 'Services-Publics'
            ],
            'attr' => ['class' => 'form-control']      
            ])
   ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
