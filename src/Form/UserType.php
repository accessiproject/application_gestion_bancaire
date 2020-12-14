<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => true,
                    'Femme' => false,
                ],
                'expanded' => true,
                'multiple' => false,
        ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('birthat', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Adresse email',
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'N° téléphone',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'N° téléphone',
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Login',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Login',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Mot de passe',
                ]
            ]);
            
        if ($options['adviser']) {
            $builder
                ->add('roles', CollectionType::class, [
                    'label' => 'Sélectionnez le profil',
                    'entry_type' => ChoiceType::class,
                    'entry_options' => [
                        'label' => false,
                        'choices' => [
                            'Administrateur' => "ROLE_ADMIN",
                            'Chargé de clientèle' => "ROLE_ADVISER"
                        ],
                        'expanded' => true,
                        'multiple' => false,
                    ]
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'adviser' => null,
        ]);
    }
}
