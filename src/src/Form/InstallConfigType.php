<?php

namespace App\Form;

use App\Entity\Resto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstallConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name of the Restaurant',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Restaurant Name'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('emailSender', EmailType::class, [
                'label' => 'EMail Sender',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'EMail Sender'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('lang', TextType::class, [
                'label' => 'Lang',
                'attr' => ['class' => 'form-control', 'id' => 'floatingInput', 'placeholder' => 'Lang'],
                'label_attr' => ['class' => 'sr-only'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary w-100 py-2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resto::class,
        ]);
    }
}
