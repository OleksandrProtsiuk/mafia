<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks

 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $body;

    /**
     * @var DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $d =new DateTime();
        $this->created = $d->setTimestamp(time());
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ChatUser", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chatUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
/*
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }*/

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getChatUser(): ?ChatUser
    {
        return $this->chatUser;
    }

    public function setChatUser(?ChatUser $chatUser): self
    {
        $this->chatUser = $chatUser;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }
}
