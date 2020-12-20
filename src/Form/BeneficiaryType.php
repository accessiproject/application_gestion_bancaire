<?php

namespace App\Form;

use App\Entity\Beneficiary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BeneficiaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('iban', TextType::class, [
                'label' => 'N° iban',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'N° iban',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Beneficiary::class,
        ]);
    }
}
