<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('birthat')
            ->add('email')
            ->add('username')
            ->add('password')
            ->add('roles', CollectionType::class, [
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
