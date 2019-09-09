<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ChatUser", mappedBy="user", cascade={"persist", "remove"})
     */
    private $chatUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getChatUser(): ?ChatUser
    {
        return $this->chatUser;
    }

    public function setChatUser(ChatUser $chatUser): self
    {
        $this->chatUser = $chatUser;

        // set the owning side of the relation if necessary
        if ($this !== $chatUser->getUser()) {
            $chatUser->setUser($this);
        }

        return $this;
    }
}
