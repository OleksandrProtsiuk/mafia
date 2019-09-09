<?php


namespace App\Controller;


use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Room controller.
 * @Route("/api", name="api")
 */
class RoomController extends AbstractController
{
    /**
     * Lists all Rooms
     * @Rest\Get("/rooms")
     *
     * @return Response
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Room::class);
        $rooms = $repository->findall();
        return $this->json($rooms, Response::HTTP_OK);
    }

    public function new(){}

    /**
     * Show room by id
     * @Rest\Get("/room/{id}")
     *
     * @return Response
     */
    public function show(Room $room)
    {
        return $this->json($room, Response::HTTP_OK);
    }

    public function edit(){}

    public function delete(){}
}