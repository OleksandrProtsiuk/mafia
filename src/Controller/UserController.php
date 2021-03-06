<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User controller.
 * @Route("/api", name="api")
 */
class UserController extends AbstractController
{
    /**
     * Lists all Users
     * @Rest\Get("/users", name="users")
     *
     * @return Response
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findall();
        return $this->json($users, Response::HTTP_OK);
    }

    /**
     * Create User.
     * @Rest\Post("/user/new", name="user_new")
     *
     * @return Response
     */
    public function new(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json(['status' => 'ok'], Response::HTTP_CREATED);
        }

        return $this->json(['status' => 'Errors', 'errors' => $form->getErrors()], Response::HTTP_FORBIDDEN);
    }

    /**
     * Show User by id
     * @Rest\Get("/user/{id}", name="user_show")
     * @return Response
     */
    public function show(User $user): Response
    {
        if($user){
            return $this->json(['post' => $user,], Response::HTTP_OK);
        }
        return $this->json(['Error' => 'No data in DB',], Response::HTTP_NOT_FOUND);
    }

    /**
     * Edit User
     * @Rest\Post("/user/{id}/edit", name="user_edit")
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'status' => 'ok',
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'status' => 'Error',
            'errors' => $form->getErrors($form),
        ], Response::HTTP_CONTINUE);
    }

    /**
     * Delete User
     * @Rest\Delete("/user/{id}/del", name="user_delete")
     * @return Response
     */
    public function delete(User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['status' => 'ok'], Response::HTTP_OK);
    }

    /**
     * Test Route.
     * @Rest\Post("/echo", name="echo")
     *
     * @return Response
     */
    public function echoAction()
    {
        return $this->json([
            'msg' => 'Its alive!',
        ], Response::HTTP_OK);
    }
}