<?php

namespace App\Form;

use COM;
use App\Entity\Places;
use App\Entity\Region;
use App\Entity\Annonce;
use App\Entity\Calendar;

use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchAnnonceBigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', EntityType::class, [
                'mapped' => false,
                'class' => Region::class,
                'label' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'js-region-multiple'
                ]

            ])
            ->add('departement', EntityType::class, [
                'mapped' => false,
                'class' => Departement::class,
                'label' => false,
                'required' => false,
                'multiple' => true
            ])
            ->add('city', TextType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre ville'
                ]
            ])
            // ->add('calendar', EntityType::class, [
            //     'mapped' => false,
            //     'class' => Calendar::class,
            //     'label' => false,
            //     'required' => false,
            //     'multiple' => true

            // ])
            ->add('places', EntityType::class, [
                'mapped' => false,
                'class' => Places::class,
                'label' => false,
                'required' => false,
                'multiple' => true

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
