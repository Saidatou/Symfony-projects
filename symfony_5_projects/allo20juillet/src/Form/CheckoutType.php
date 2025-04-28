<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //nous souhaitons recupéré l'addresse de l'utisateur et le coach choisi
        //on va afficher les addresses de l'utilusateur et ceux du coachs
        $user = $options['user'];
        $builder
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'required' => true,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            //informations sur coach
            ->add('name')
            ->add('city')
            ->add('id')
            //autre informations. je veux que ce soit optionnel
            ->add('informations', TextareaType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here. Pour la clé ‘user’ on lui donne un tableau vide afin d’éviter 
            //des erreurs d’affichage. Si nous avons plusieurs options, nous allons définir une valeur par défaut 
            //pour chacun des options
            'user' => array(),
        ]);
    }
}
