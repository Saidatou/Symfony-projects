<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SharePostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender_name', TextType::class, [
                'label'=>'Name',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['min'=>3])
                ]
                ])
            ->add('sender_email', EmailType::class, [
                'label'=>'Email',
                'constraints'=>[
                    new NotBlank(),
                    new Email,
                ]
                ])
            ->add('receiver_email', EmailType::class, [
                'label'=>"Your friend's email",
                'constraints'=>[
                    new NotBlank(),
                    new Email
                ]
                ])
            ->add('sender_comments', TextareaType::class, [
                'label'=>'Comments',
                'help'=>'Leave it blank if you want (optionel)'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
