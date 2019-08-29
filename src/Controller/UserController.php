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
     * @Rest\Get("/users")
     *
     * @return Response
     */
    public function getUsersAction()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findall();
        return $this->json($users, Response::HTTP_OK);
    }

    /**
     * Create User.
     * @Rest\Post("/user")
     *
     * @return Response
     */
    public function postUserAction(Request $request)
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
     * Test Route.
     * @Rest\Post("/echo")
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