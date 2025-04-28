<?php

namespace App\Form;

use App\Entity\HomeSlider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeSliderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title')
            ->add('description')
            ->add('buttonMessage')
            ->add('buttonURL')
            ->add('imageFile', VichImageType::class, [
                "required" => true,
                'allow_delete' => true,
                'delete_label' => "Supprimer l'image téléchargée",
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'help' => "Cette image illustrera votre évènement",
                'label' => "Fichier"
            ])
            ->add('isDisplayed');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeSlider::class,
        ]);
    }
}
