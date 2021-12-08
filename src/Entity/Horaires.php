<?php

namespace App\Entity;

use App\Repository\HorairesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=HorairesRepository::class)
 */
class Horaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("horaires")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Poubelles::class, inversedBy="horaires")
     * @Groups("horaires")
     */
    private $poubelles;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("horaires")
     */
    private $passage;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoubelles(): ?Poubelles
    {
        return $this->poubelles;
    }

    public function setPoubelles(?Poubelles $poubelles): self
    {
        $this->poubelles = $poubelles;

        return $this;
    }

    public function getPassage(): ?\DateTimeInterface
    {
        return $this->passage;
    }

    public function setPassage(\DateTimeInterface $passage): self
    {
        $this->passage = $passage;

        return $this;
    }

}
