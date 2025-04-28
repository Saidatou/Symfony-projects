<?php

namespace App\Form;



use App\Entity\Places;
use App\Entity\Region;
use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('mots', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer les mots clés'
                ],
                //choisir si c'est le mots clés ou le département ou le lieu
                'required' => false
            ])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'placeholder' => 'par département'
            ])

            // ->add('places', EntityType::class, [
            //     'mapped' => false,
            //     'class' => Places::class,
            //     'label' => false,
            //     'required' => false,
            //     'placeholder' => 'lieu de séance'

            // ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
