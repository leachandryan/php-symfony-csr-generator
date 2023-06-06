<?php

namespace App\Form;

use App\DTO\CSRData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CSRFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commonName', TextType::class, [
                'label' => 'Common Name (CN) *',
                'attr' => [
                    'placeholder' => 'example.com',
                ],
            ])
            ->add('organization', TextType::class, [
                'label' => 'Organization (O) *',
                'attr' => [
                    'placeholder' => 'Your Company Name',
                ],
            ])
            ->add('organizationalUnit', TextType::class, [
                'label' => 'Organizational Unit (OU)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'IT Department',
                ],
            ])
            ->add('locality', TextType::class, [
                'label' => 'Locality (L)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'City',
                ],
            ])
            ->add('state', TextType::class, [
                'label' => 'State/Province (ST)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'State',
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'Country (C) *',
                'attr' => [
                    'placeholder' => 'US',
                    'maxlength' => 2,
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'required' => false,
                'attr' => [
                    'placeholder' => 'admin@example.com',
                ],
            ])
            ->add('keySize', ChoiceType::class, [
                'label' => 'Key Size',
                'choices' => [
                    '2048 bits (Standard)' => '2048',
                    '4096 bits (Extra Secure)' => '4096',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CSRData::class,
        ]);
    }
}