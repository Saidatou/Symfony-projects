<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                "label" => "Votre adresse email",
                "attr" => [
                    "class" => " form-control"
                ]
            ])
            ->add('firstname', null, [
                "label" => "Votre prénom",
                "attr" => [
                    "class" => " form-control"
                ]
            ])
            ->add('lastname', null,  [
                "label" => "Votre nom",
                "attr" => [
                    "class" => " form-control"
                ]
            ]);
        $builder
            ->add('agreeTerms', CheckboxType::class, [
                "label" => "Acceptez les Conditions Générales d'Utilisation",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Acceptez les CGU.',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'Votre mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'first_options'  => ['label' => 'Votre mot de passe (6 caractères minimum)'],
                'second_options' => ['label' => 'confirmer votre mot de passe (6 caractères minimum)'],
            ]);
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,

        ]);
    }
}
