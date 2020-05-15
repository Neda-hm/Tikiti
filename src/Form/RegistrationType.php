<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Ticket;
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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;
use App\Form\EntrepriseRegisterType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder ->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                
            ]
        ]);
        $builder ->add('prenom', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                
            ]
        ]);

        $builder ->add('tel', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                
            ]
        ]);
        $builder ->add('adresse', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                
            ]
        ]);

        $builder ->add('ville', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                
            ]
        ]);
        $builder ->add('codePostale', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                
            ]
        ]);
        $builder ->add('lng', HiddenType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ]);
        $builder ->add('lat', HiddenType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        $builder->add('roles', CollectionType::class, [
            // each entry in the array will be an "email" field
            'entry_type' => HiddenType::class,
            // these options are passed to each "email" type
            'entry_options' => [
                'attr' => ['value' => 'ADMIN_ENTREPRISE'],
            ],
        ]);

        $builder ->add('userPro', EntrepriseRegisterType::class);
       
    



        }

    
   public function getParent()

   {
       return 'FOS\UserBundle\Form\Type\RegistrationFormType';
   }

   public function getBlockPrefix()

   {
       return 'app_user_registration';
   }

   public function getName()

   {
       return $this->getBlockPrefix();
   }


}
