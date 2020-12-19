<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/adviser/index", name="user_adviser_list")
     * @Route("/adviser/customer/index", name="user_customer_list")
     * @param $_route
     */
    public function user_list($_route)
    {
        if ($_route == "user_adviser_list") {
            $render = "user/adviser/list.html.twig";
            $users = $this->getDoctrine()->getRepository(User::class)->findByRoleThatSucksLess("ADVISER");
        } else {
            $render = "user/customer/list.html.twig";
            $users = $this->getDoctrine()->getRepository(User::class)->findByRoleThatSucksLess("CUSTOMER");
        }
        return $this->render($render, [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/adviser/new", name="user_adviser_new")
     * @Route("/customer/new", name="user_customer_new")
     * @Route("/new", name="user_new")
     * @param $_route
     */
    public function user_new($_route, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($_route == "user_adviser_new") {
            $redirectroute = 'user_adviser_list';
            $render = '/user/adviser/new.html.twig';
        } else if ($_route == "user_customer_new") {
            $redirectroute = 'user_customer_list';
            $render = '/user/customer/new.html.twig';
        } else {
            $redirectroute = 'home_index';
            $render = '/user/customer/new.html.twig';
        }
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'adviser' => $_route == "user_adviser_new" ? 'true' : null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($_route == "user_customer_new" or $_route == "user_new")
                $user->setRoles(["ROLE_CUSTOMER"]);
                
            $password = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
            $user->setPassword($password);
            $user->updatedTimestamps();
            $entityManager->persist($user);
            $entityManager->flush();    
            
            return $this->redirectToRoute($redirectroute);
        }

        return $this->render($render, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adviser/show/{id}", name="user_adviser_show")
     * @Route("/adviser/customer/show/{id}", name="user_customer_show")
     * @param $_route
     */
    public function user_show($_route, User $user): Response
    {
        $render = $_route == "user_adviser_show" ? 'user/adviser/show.html.twig' : 'user/customer/show.html.twig';
        return $this->render($render, [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/customer/profil", name="user_customer_profil")
     */
    public function user_profil(Request $request) {
        //$user = $this->getDoctrine()->getRepository(User::class)->find($user);
        $session = $request->getSession();
        var_dump($session->getUsername());
    }

    /**
     * @Route("/adviser/edit/{id}", name="user_adviser_edit")
     * @Route("/adviser/customer/edit/{id}", name="user_customer_edit")
     * @param $_route
     */
    public function user_edit($_route, Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
            $user->setPassword($password);
            $user->updatedTimestamps();
            $this->getDoctrine()->getManager()->flush();

            $redirectroute = $_route == "user_adviser_edit" ? 'user_adviser_list' : 'user_customer_list';
            return $this->redirectToRoute($redirectroute);
        }

        $render = $_route == "user_adviser_edit" ? 'user/adviser/edit.html.twig' : 'user/customer/edit.html.twig';
        return $this->render($render, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adviser/delete/{id}", name="user_adviser_delete")
     * @Route("/adviser/customer/delete/{id}", name="user_customer_delete")
     * @param $_route
     */
    public function user_delete($_route, $id, Request $request, EntityManagerInterface $manager)
    {
        $user = $manager->getRepository(User::class)->find($id);
        $manager->remove($user);
        $manager->flush();
        $route = $_route == "user_adviser_delete" ? 'user_adviser_list' : 'user_customer_list';
        return $this->redirectToRoute($route, [ 'user' => $user ]);
    }
}
