<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Region;
use App\Form\DepartementType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'name', type: TextType::class)
            ->add('departements', CollectionType::class, [
                'entry_type' => DepartementType::class,
                'label' => 'Département',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])


            // ->add('departements', CollectionType::class, [
            //     'entry_type' => DepartementType::class,
            //     'label' => 'Département',
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false
            // ])
            ->add('valider', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
