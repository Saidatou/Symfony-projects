<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    
    {
        dump($options);


        $builder
           
            ->add('nom', null,  [
                "label"=>"Votre nom",
                "attr"=>[
                    "class"=>" form-control"
                ]
            ])
            
            ->add('prenom', null, [
                "label"=>"Votre prénom",
                "attr"=>[
                    "class"=>" form-control"
                ]
            ])
           
            ->add('email', EmailType::class, [
                "label"=>"Votre adresse email",
                "attr"=>[
                    "class"=>" form-control"
                ]
            ])
           

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les deux mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Votre mot de passe'],
                'second_options' => ['label' => 'confirmation de votre mot de passe'],
            ]);

           if($options["isAdmin"]){
	        $builder->add('roles', ChoiceType::class, [
	            "choices"=>[
	                 "Admin"=>"ROLE_ADMIN",
                     "Editor"=>"ROLE_EDITOR",
	                //"User"=>"ROLE_USER"
	                    ],
	            "multiple"=>true,
	            "expanded"=>true,
	            "attr"=>[
	            "class"=>"form-check"
	                    ],
	            
	            "label"=>"Droits de l'utilisateur"
	             ]);
	            }
                
                if ($options["isEditor"]){
                    $builder->add('roles', ChoiceType::class, [
                        "choices"=>[
                            
                            "Editor"=>"ROLE_EDITOR",
                            //"User"=>"ROLE_USER"
                                ],
                        "multiple"=>true,
                        "expanded"=>true,
                        "attr"=>[
                        "class"=>"form-check"
                                ],
                        
                        "label"=>"Droits de l'utilisateur"
                         ]);
                        }
    } 


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'isEditor'=>false,
            'isAdmin'=>false
        ]);
    }
}
