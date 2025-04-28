<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('lastname');
        $builder
            ->add('username', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Nom d'utilisateur",
                "help" => "Si vous êtes coach, C'est ce nom sera visible aux internautes",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ]);


        $builder->add('roles', ChoiceType::class, [
            "choices" => [
                "Admin" => "ROLE_ADMIN",
                "Coach" => "ROLE_COACH",
                //"User"=>"ROLE_USER"
            ],
            "multiple" => true,
            "expanded" => true,
            "attr" => [
                "class" => "form-check"
            ],

            "label" => "Droits de l'utilisateur"
        ]);




        $builder->add('password', RepeatedType::class, [
            "type" => PasswordType::class,
            "first_options" => [
                "label" => "Votre mot de passe",
                "label_attr" => [
                    "class" => "form-label"
                ], "attr" => [
                    "class" => "form-control"
                ]
            ],
            "second_options" => [
                "label" => "Répétez votre mot de passe",
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ]
            ],
            "invalid_message" => "Les deux mots de passes ne matchent pas !!",
            "required" => true,

        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
