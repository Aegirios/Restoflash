<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatabaseConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sgbd', TextType::class, [
                'label' => 'Database management system',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Database Management System'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('user', TextType::class, [
                'label' => 'User',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'User'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Password'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('host', TextType::class, [
                'label' => 'Host',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Host'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('port', TextType::class, [
                'label' => 'Port',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Port'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('version', TextType::class, [
                'label' => 'Version',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Version'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('mariadb', CheckboxType::class, [
                'label' => 'MariaDB',
                'required' => 'false',
                'attr' => ['class' => 'form-check-input', 'id' => 'floatingInput', 'placeholder' => 'MariaDB', 'required' => 'false'],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('database', TextType::class, [
                'label' => 'Database name',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Database name'],
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
