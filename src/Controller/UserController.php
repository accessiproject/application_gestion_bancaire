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

class UserController extends AbstractController
{
    /**
     * @Route("/adviser/index", name="user_adviser_list")
     * @Route("/customer/index", name="user_customer_list")
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
     * @Route("/adviser/new", name="user_adviser_new", methods={"GET","POST"})
     * @Route("/customer/new", name="user_customer_new", methods={"GET","POST"})
     * @param $_route
     */
    public function user_new($_route, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'adviser' => $_route == "user_adviser_new" ? 'true' : null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($_route == "user_customer_new")
                $user->setRoles(["ROLE_CUSTOMER"]);

            //$password = $this->encoder->encodePassword($user, 'kevin');
            //$user->setPassword($password);
            $user->updatedTimestamps();
            $entityManager->persist($user);
            $entityManager->flush();

            $route = $_route == "user_adviser_new" ? 'user_adviser_list' : 'user_customer_list';
            return $this->redirectToRoute($route);
        }

        $render = $_route == "user_adviser_new" ? 'user/adviser/new.html.twig' : 'user/customer/new.html.twig';
        return $this->render($render, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adviser/show/{id}", name="user_adviser_show")
     * @Route("/customer/show/{id}", name="user_customer_show")
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
     * @Route("/adviser/edit/{id}", name="user_adviser_edit")
     * @Route("/customer/edit/{id}", name="user_customer_edit")
     
     */
    public function user_edit($_route, Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$password = $this->encoder->encodePassword($user, 'kevin');
            //$user->setPassword($password);
            $user->updatedTimestamps();
            $this->getDoctrine()->getManager()->flush();

            $route = $_route == "user_adviser_new" ? 'user_adviser_list' : 'user_customer_list';
            return $this->redirectToRoute($route);
        }

        $render = $_route == "user_adviser_edit" ? 'user/adviser/edit.html.twig' : 'user/customer/edit.html.twig';
        return $this->render($render, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/adviser/delete/{id}", name="user_adviser_delete", methods={"DELETE"})
     * @Route("/customer/delete/{id}", name="user_customer_delete", methods={"DELETE"})
     */
    public function user_delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $route = $_route == "user_adviser_delete" ? 'user_adviser_list' : 'user_customer_list';
        return $this->redirectToRoute($route);
    }
}
