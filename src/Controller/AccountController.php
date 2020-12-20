<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AccountController extends AbstractController
{
    /**
    * @Route("/customer/{id}/account/index", name="account_list") 
     */
    public function account_index($id): Response
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findByUser($id);
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'accounts' => $accounts,
			'id' => $id,
        ]);
    }
	
	    /**
     * @Route("/adviser/customer/{id}/account/open", name="account_open")
     */
    public function account_open($id, Request $request): Response
    {
        
        
        $account = new Account();
		$user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
			$account->setBalance(0);
			$account->setUser($user);
            $account->updatedTimestamps();
            $entityManager->persist($account);
            $entityManager->flush();    
            
            return $this->redirectToRoute('account_list', [
            'id' => $id,
        ]);
        }

        return $this->render('account/open.html.twig', [
            'account' => $account,
			'user' => $user,
            'form' => $form->createView(),
        ]);
    }
	
	    /**
     * @Route("/adviser/account/delete/{id}", name="account_delete")
     */
    public function account_delete($id, Request $request, EntityManagerInterface $manager)
    {
        $account = $manager->getRepository(Account::class)->find($id);
		$user = $account->getUser();
        $manager->remove($account);
        $manager->flush();
        return $this->redirectToRoute('account_list', [ 'id' => $user->getId() ]);
    }
}
