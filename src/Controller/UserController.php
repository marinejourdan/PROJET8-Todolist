<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     * 
     */
    public function listAction(): Response
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App\Entity\User')->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * 
     */
    public function createAction(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $username = $request->get('user[username]');

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        $submitted = $form->isSubmitted();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        $errors = $form->getErrors(true);

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * 
     */
    public function editAction(User $user, Request $request,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
