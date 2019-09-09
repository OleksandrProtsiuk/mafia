<?php

namespace App\Controller;

use App\Entity\ChatUser;
use App\Form\ChatUserType;
use App\Repository\ChatUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ChatUser controller.
 * @Route("/api", name="api")
 */
class ChatUserController extends AbstractController
{
    /**
     * Show all Posts
     * @Route("/chat/users", name="chatusers_index", methods={"GET"})
     */
    public function index(ChatUserRepository $chatUserRepository): Response
    {
        return $this->json(['posts' => $chatUserRepository->findAll(),], Response::HTTP_OK);
    }


    /**
     * Create new ChatUser
     * @Rest\Post("/chat/user/new" , name="chatUser_new")
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $chatUser = new ChatUser();
        $form = $this->createForm(ChatUserType::class, $chatUser);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chatUser);
            $entityManager->flush();

            return $this->json([
                'status' => 'ok',
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'status' => 'Error',
            'errors' => $form->getErrors($form)], Response::HTTP_FAILED_DEPENDENCY);
    }

    /**
     * Show ChatUser by id
     * @Rest\Get("/chat/user/{id}", name="chatUser_show")
     * @return Response
     */
    public function show(ChatUser $chatUser): Response
    {
        if($chatUser){
            return $this->json(['post' => $chatUser,], Response::HTTP_OK);
        }
        return $this->json(['Error' => 'No data in DB',], Response::HTTP_NOT_FOUND);
    }

    /**
     * Edit ChatUser
     * @Rest\Post("/chat/user/{id}/edit", name="chatUser_edit")
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, ChatUser $chatUser): Response
    {
        $form = $this->createForm(ChatUserType::class, $chatUser);
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
     * Delete ChatUser
     * @Rest\Delete("/chat/user/{id}/del", name="chatUser_delete")
     * @return Response
     */
    public function delete(ChatUser $chatUser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($chatUser);
        $entityManager->flush();

        return $this->json(['status' => 'ok'], Response::HTTP_OK);
    }
}
