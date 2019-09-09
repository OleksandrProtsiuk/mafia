<?php

namespace App\Controller;

use App\Entity\ChatUser;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Post controller.
 * @Route("/api", name="api")
 */
class PostController extends AbstractController
{
    /**
     * Show all Posts
     * @Route("/posts", name="posts_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->json(['posts' => $postRepository->findAll(),], Response::HTTP_OK);
    }

    /**
     * Create new post
     * @Rest\Post("/post/new")
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $data = json_decode($request->getContent(), true);

        $roomId= $this->getDoctrine()
            ->getRepository(ChatUser::class)
            ->find($data['chatUser'])
            ->getRoom();
        $post->setRoom($roomId);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
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
     * Show post by id
     * @Rest\Get("/post/{id}", name="post_show")
     * @return Response
     */
    public function show(Post $post): Response
    {
        if($post){
            return $this->json(['post' => $post,], Response::HTTP_OK);
        }
        return $this->json(['Error' => 'No data in DB',], Response::HTTP_NOT_FOUND);
    }

    /**
     * Edit Post
     * @Rest\Post("/post/{id}/edit", name="post_edit")
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Post $post): Response
    {
        $d =new DateTime();
        $post->setCreated($d->setTimestamp(time()));
        $form = $this->createForm(PostType::class, $post);
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
     * Delete Post
     * @Rest\Delete("/post/{id}/del", name="post_delete")
     * @return Response
     */
    public function delete(Post $post): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->json(['status' => 'ok'], Response::HTTP_OK);
    }
}
