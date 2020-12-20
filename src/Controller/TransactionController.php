<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Account;
use App\Repository\TransactionRepository;
use App\Repository\AccountRepository;
use App\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TransactionController extends AbstractController
{

/**
     * @Route("/customer/account/{account_id}/transactin/new", name="transaction_new")
     */
    public function transaction_new($account_id, Request $request): Response
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($account_id);
        $transaction = new Transaction();
		$user = $this->getUser();
        $form = $this->createForm(TransactionType::class, $transaction, [
		'user_id' => $user->getId(),
		'account_balance' => $account->getBalance(),
		]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
			$transaction->setUser($user);
            $transaction->achievedat();
            $entityManager->persist($transaction);
            $entityManager->flush();    
            
            return $this->redirectToRoute('account_list', [
            'id' => $user->getId(),
        ]);
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }
}