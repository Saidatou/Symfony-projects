<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;

class ServiceType extends AbstractType
{
    public function __construct(private FormListenerFactory $factory){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('icon',TextType::class,[
                'empty_data' => ''
            ])
            ->add('title', TextType::class,[
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('thumbnailFile', FileType::class
           
            )
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label'=>'name',
                'expanded'=>true,
                'autocomplete'=>true,
            ])
            // ->add('category', CategoryAutocompleteField::class)
            ->add('short_desc',TextType::class,[
                'empty_data' => ''
            ])
            ->add('content',TextareaType::class,[
                'empty_data' => ''
            ])
            ->add('duration')
           
            ->add('quantities', CollectionType::class, [
                'entry_type' => QuantityType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller' => 'form-collection',
                    'data-form-collection-add-label-value' => 'Ajouter un ingredient',
                    'data-form-collection-delete-label-value' => 'Supprimer un ingredient',
                    
                ]
            ])

            ->add('save', SubmitType::class)
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->factory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->factory->timestamps())
        ;
    }

    // public function attachTimestamps(PostSubmitEvent $event):void
    // {
    //     $data = $event->getData();
    //     if(!($data instanceof Service)){
    //         return;
    //     }

    //     $data->setUpdatedAt(new \DateTimeImmutable());
    //     if(!$data->getId()){
    //         $data->setCreatedAt(new \DateTimeImmutable());
    //     }
       
    // }

    // public function autoSlug(PreSubmitEvent $event):void
    // {
    //     $data = $event->getData();
    //     if(empty($data['slug'])){
    //         $slugger = new AsciiSlugger();
    //         $data['slug'] = strtolower($slugger->slug($data['title']));
    //         $event->setData($data);
    //     }
    // }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
            // 'validation_groups' => ['Default','Extra']
        ]);
    }
}
