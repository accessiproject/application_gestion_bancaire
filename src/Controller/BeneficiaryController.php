<?php

namespace App\Controller;

use App\Entity\Beneficiary;
use App\Repository\BeneficiaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\BeneficiaryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BeneficiaryController extends AbstractController
{
	
		/**
    * @Route("/customer/beneficiary/index", name="beneficiary_list") 
     */
    public function beneficiary_index()
    {
		$user = $this->getUser();
		$beneficiaries = $this->getDoctrine()->getRepository(Beneficiary::class)->findByUser($user->getId());
        return $this->render('beneficiary/index.html.twig', [
            'controller_name' => 'BeneficiaryController',
			'beneficiaries' => $beneficiaries,
        ]);
    }
	
	/**
     * @Route("/customer/beneficiary/new", name="beneficiary_new")
     */
    public function beneficiary_new(Request $request): Response
    {
        
        $beneficiary = new Beneficiary();
        $form = $this->createForm(BeneficiaryType::class, $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
			$user = $this->getUser();
			$beneficiary->setUser($user);
            $beneficiary->updatedTimestamps();
            $entityManager->persist($beneficiary);
            $entityManager->flush();    
            
            return $this->redirectToRoute('beneficiary_list', [
            'id' => $user->getId(),
        ]);
        }

        return $this->render('beneficiary/new.html.twig', [
            'beneficiary' => $beneficiary,
            'form' => $form->createView(),
        ]);
    }
	
	
		    /**
     * @Route("/customer/beneficiary/delete/{id}", name="beneficiary_delete")
     */
    public function beneficiary_delete($id, Request $request, EntityManagerInterface $manager)
    {
        $beneficiary = $manager->getRepository(Beneficiary::class)->find($id);
		$user = $beneficiary->getUser();
        $manager->remove($beneficiary);
        $manager->flush();
        return $this->redirectToRoute('beneficiary_list', [ 'id' => $user->getId() ]);
    }

}
