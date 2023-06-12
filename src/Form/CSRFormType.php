<?php

namespace App\Form;

use App\DTO\CSRData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CSRFormType extends AbstractType
{
    private const KEY_CONFIGS = [
        'rsa' => [
            '2048' => '2048 bits (Standard)',
            '3072' => '3072 bits (NIST 2030+)',
            '4096' => '4096 bits (Extra Secure)'
        ],
        'ecc' => [
            'prime256v1' => 'prime256v1 (Standard)',
            'secp384r1' => 'secp384r1 (More Secure)',
            'secp521r1' => 'secp521r1 (Extra Secure)'
        ]
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commonName', TextType::class, [
                'label' => 'Common Name (CN) *',
                'attr' => ['placeholder' => 'example.com'],
            ])
            ->add('organization', TextType::class, [
                'label' => 'Organization (O) *',
                'attr' => ['placeholder' => 'Your Company Name'],
            ])
            ->add('organizationalUnit', TextType::class, [
                'label' => 'Organizational Unit (OU)',
                'required' => false,
                'attr' => ['placeholder' => 'IT Department'],
            ])
            ->add('locality', TextType::class, [
                'label' => 'Locality (L)',
                'required' => false,
                'attr' => ['placeholder' => 'City'],
            ])
            ->add('state', TextType::class, [
                'label' => 'State/Province (ST)',
                'required' => false,
                'attr' => ['placeholder' => 'State'],
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
                'attr' => ['placeholder' => 'admin@example.com'],
            ])
            ->add('keyType', ChoiceType::class, [
                'label' => 'Key Type',
                'choices' => [
                    'RSA' => 'rsa',
                    'ECC' => 'ecc'
                ],
                'attr' => ['class' => 'key-type-select']
            ])
            ->add('keyConfig', ChoiceType::class, [
                'label' => 'Key Configuration',
                'choices' => [
                    '2048 bits (Standard)' => '2048',
                    '3072 bits (NIST 2030+)' => '3072',
                    '4096 bits (Extra Secure)' => '4096',
                    'prime256v1 (Standard)' => 'prime256v1',
                    'secp384r1 (More Secure)' => 'secp384r1',
                    'secp521r1 (Extra Secure)' => 'secp521r1'
                ],
                'attr' => ['class' => 'key-config-select']
            ]);

        // Add listener to update keyConfig choices based on keyType
        $builder->get('keyType')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm()->getParent();
            $keyType = $event->getForm()->getData();

            if (!$form) {
                return;
            }

            $choices = self::KEY_CONFIGS[$keyType] ?? self::KEY_CONFIGS['rsa'];
            $form->add('keyConfig', ChoiceType::class, [
                'label' => $keyType === 'rsa' ? 'Key Size' : 'Curve',
                'choices' => array_flip($choices),
                'attr' => ['class' => 'key-config-select']
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CSRData::class,
        ]);
    }
}