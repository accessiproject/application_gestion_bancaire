<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Entity\Beneficiary;
use App\Entity\Account;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\BeneficiaryRepository;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user_id = $options['user_id'];
		$account_balance = $options['account_balance'];
		
		$builder
            ->add('label', TextType::class, [
                'label' => 'Libellé du virement',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Libellé du virement',
                ],
				])
				->add('debit', IntegerType::class, [
                'label' => 'Montant du virement',
                'attr' => [
                    'required' => true,
					'max' => $account_balance,
					'min' => 0,
                ],
				])
				->add('choice', ChoiceType::class, [
                'label' => 'Transférer la somme vers :',
                'choices' => [
                    'Un compte bancaire' => true,
                    'Un bénéficiaire' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => true,
        ])
            ->add('beneficiary', EntityType::class, [
			'label' => 'Sélectionnez l\'un de vos bénéficiaires',
                        'class' => Beneficiary::class,
                        'expanded' => false,
                        'multiple' => false,
						'query_builder' => function (EntityRepository $beneficiary) use ($user_id) {
                            return $beneficiary->createQueryBuilder('b')
                                ->where('b.user = :user')
                                ->setParameter('user', $user_id);
                        },
                        'choice_label' => 'iban',
                    ])
					->add('account', EntityType::class, [
                        'label' => 'Choisissez un compte bancaire parmi vos livrets bancaires ouverts',
						'class' => Account::class,
                        'expanded' => false,
                        'multiple' => false,
						'query_builder' => function (EntityRepository $account) use ($user_id) {
                            return $account->createQueryBuilder('a')
                                ->where('a.user = :user')
                                ->setParameter('user', $user_id);
                        },
                        'choice_label' => 'name',
                    ])
					;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
			'user_id' => null,
			'account_balance' => null,
        ]);
    }
}
