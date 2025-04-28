<?php

namespace App\Form;

use App\Entity\Places;
use App\Entity\Region;
use App\Entity\Annonce;
use App\Entity\Departement;
use App\Form\DepartementType;
use PhpParser\Parser\Multiple;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Nom d'utilisateur",
                "help" => "Ce nom sera visible aux internautes",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])
            ->add('title', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Titre de votre message",
                "help" => "Veuillez écrire un titre attrayant",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])
            // ->add('slug')
            ->add('description', TextareaType::class, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Description",
                "help" => "Parlez-nous de votre méthodologie de travail",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])

            ->add('formation', TextareaType::class, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Formation",
                "help" => "Parlez-nous de vos formations",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])
            ->add('experience', TextareaType::class, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Votre expérience professionnelle",
                "help" => "Quel est votre parcours en coaching sportif ?",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])

            ->add('isInYourHome', CheckboxType::class, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "help" => "Accepterez-vous les séances où c'est vous qui choisissez le lieu ?",
                "label" => "Séance chez vous",

            ])
            ->add('isInClientHome', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Séance chez le client",
                "help" => "Accepterez-vous les séances où c'est le client qui choisi le lieu ?",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])

            ->add('isInByVisio', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Séance en Visio",
                "help" => "Accepterez-vous les séances en visio ?",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])
            // ->add('places', EntityType::class, [
            //     'class' => Places::class,
            //     'mapped' => true,
            //     "label_attr" => [
            //         "class" => "form-label"
            //     ],
            //     "attr" => [
            //         "class" => "form-control"
            //     ],
            //     "multiple" => true,
            //     "label" => "Veuillez confirmez les choix faites plus haut",
            //     "help" => " Les internautes feront leur recherche à partir de cette confirmation",
            // ])




            // ajout du champs région
            ->add('region', EntityType::class, [
                'mapped' => false,
                'class' => Region::class,
                'choice_label' => 'name',
                'placeholder' => 'Région',
                'label' => 'Région',
                'required' => false
            ])
            //ajout du champs departement
            ->add('departement', ChoiceType::class, [
                'placeholder' => 'Département (Choisir une région)',
                'required' => false
            ])
            // ->add('departement', EntityType::class, [
            //     'mapped' => false,
            //     'class' => Departement::class,
            //     'placeholder' => 'Département (Choisir une région)',
            //     'label' => 'Département',
            //     'by_reference' => false
            // ])

            ->add('city', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "La ville où vous souhaitez exercez le plus",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])
            // ->add('places', CollectionType::class, [
            //     'mapped' => true,
            //     'class' => Places::class,
            //     'placeholder' => ' Lieu de séance (Choisir les lieux)',
            //     'label' => 'Où ?',

            // ])





            ->add('coachingPlaces', TextareaType::class, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Séances chez vous. Veuillez renseigner au moins une adresses où les clients peuvent se rendre ",
                "help" => " Permettez aux clients de se projeter en leur fournissant un endroit de votre choix",
                "help_attr" => [
                    "class" => "form-text"
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                "required" => false,
                'allow_delete' => true,
                'delete_label' => "Supprimer l'image téléchargée",
                'download_uri' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'help' => "Cette image illustrera votre profile",
                'label' => " Une photo de profile"
            ])
            ->add('cvFile', VichFileType::class, [
                "required" => true,
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Votre cv",
                "help" => "Nous en aurons besoins pour vérifier les informations renseignées",
                "help_attr" => [
                    "class" => "form-text"
                ],

            ])
            ->add('ribFile', VichFileType::class, [
                "required" => true,
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "votre RIB",
                "help" => "Nous en aurons besoins pour vous faire le reglement",
                "help_attr" => [
                    "class" => "form-text"
                ],

            ])
            ->add('siretnumber', null, [
                "label_attr" => [
                    "class" => "form-label"
                ],
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Votre numéro siret",
                "help" => "Si vous n'en avez pas pour l'instant, ce n'est pas grave",
                "help_attr" => [
                    "class" => "form-text"
                ]
            ])
            //->add('maj')
            // ->add('active')
            ->add('valider', SubmitType::class);

        $formModifier = function (FormInterface $form, Region $region = null) {
            $departements = null === $region ? [] : $region->getDepartements();

            $form->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choices' => $departements,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Département (Choisir une région)',
                'attr' => ['class' => 'custom-select'],
                'label' => 'Département'

            ]);
        };

        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $region = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $region);
            }
        );

        // //->FormModifier aura besoin de récupérer les informations du formulaire et la région
        // //le formulaire est représenté par $event et la region par $région
        // $formModifier = function (FormInterface $form, Region $regions = null) {
        //     //on récupère la liste des départements stockés en base de données. Si région est null,
        //     //dans ce cas je renvoie un tableau vide sinon je renvoie les départements
        //     $departements = null === $regions ? [] : $regions->getDepartements();

        //     // on a donc les départements que nous avons rajouté à notre formulaire
        //     $form->add('departement', EntityType::class, [
        //         'class' => Departement::class,
        //         'choices' => $departements,
        //         'required' => false,
        //         'choice_label' => 'name',
        //         'placeholder' => 'Département (Choisir une région)',
        //         'attr' => ['class' => 'custom-select'],
        //         'label' => 'Département'
        //     ]);
        // };
        // //écoute l'évenement de la région
        // $builder->get('region')->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) use ($formModifier) {
        //         $region = $event->getForm()->getData();
        //         //getForm() va chercher uniquement région car lié au Builder 
        //         //et on fait getParent() pour aller chercher le formulaire lui même et en deuxième on envoie région
        //         $formModifier($event->getForm()->getParent(), $region);
        //     }
        // );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
