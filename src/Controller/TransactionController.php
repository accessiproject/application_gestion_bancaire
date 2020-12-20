<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Form\TransactionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{

/**
     * @Route("/customer/transactin/new", name="transaction_new")
     */
    public function transaction_new(Request $request): Response
    {
        
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
			$user = $this->getUser();
			$transaction->setUser($user);
            $transactin->achievedat();
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
