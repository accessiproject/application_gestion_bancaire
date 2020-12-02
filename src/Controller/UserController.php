<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/user/index", name="user_index")
     */
    public function user_index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll(); //request to retrieve all surveys
        return $this->render('user/index.html.twig', [ //return to the template
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }
}