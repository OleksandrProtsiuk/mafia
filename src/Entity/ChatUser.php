<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatUserRepository")
 */
class ChatUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="chatUser", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gameRole;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gameStatus;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Room", inversedBy="chatUser", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="chatUser", orphanRemoval=true)
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGameRole(): ?string
    {
        return $this->gameRole;
    }

    public function setGameRole(string $gameRole): self
    {
        $this->gameRole = $gameRole;

        return $this;
    }

    public function getGameStatus(): ?bool
    {
        return $this->gameStatus;
    }

    public function setGameStatus(bool $gameStatus): self
    {
        $this->gameStatus = $gameStatus;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setChatUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getChatUser() === $this) {
                $post->setChatUser(null);
            }
        }

        return $this;
    }
}
