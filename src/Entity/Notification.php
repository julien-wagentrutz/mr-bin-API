<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("user")
     */
    private $timeNotif;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
     * @Groups("user")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Poubelles::class, inversedBy="notifications")
     * @Groups("user")
     */
    private $poubelle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeNotif(): ?int
    {
        return $this->timeNotif;
    }

    public function setTimeNotif(int $timeNotif): self
    {
        $this->timeNotif = $timeNotif;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPoubelle(): ?Poubelles
    {
        return $this->poubelle;
    }

    public function setPoubelle(?Poubelles $poubelle): self
    {
        $this->poubelle = $poubelle;

        return $this;
    }
}
