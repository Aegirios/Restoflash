<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailerConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('protocol', TextType::class, [
                'label' => 'Protocol',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Mailer Protocol'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('user', TextType::class, [
                'label' => 'username',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Username'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('password', TextType::class, [
                'label' => 'Password',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Password'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('adress', TextType::class, [
                'label' => 'Server',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Server'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('port', TextType::class, [
                'label' => 'Port',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Port'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('mailSender', EmailType::class, [
                'label' => 'EMail',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Email'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary w-100 py-2'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
