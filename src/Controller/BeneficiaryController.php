<?php

namespace App\Controller;

use App\Entity\Beneficiary;
use App\Repository\BeneficiaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		$beneficiaries = $this->getDoctrine()->getRepository(Beneficiary::class)->findByUser(84);
        return $this->render('beneficiary/index.html.twig', [
            'controller_name' => 'BeneficiaryController',
			'beneficiaries' => $beneficiaries,
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
