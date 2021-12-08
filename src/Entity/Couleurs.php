<?php

namespace App\Entity;

use App\Repository\CouleursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouleursRepository::class)
 */
class Couleurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("horaires")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("horaires")
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Poubelles::class, mappedBy="couleur")
     */
    private $poubelles;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("horaires")
     */
    private $class;

    public function __construct()
    {
        $this->poubelles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }



    /**
     * @return Collection|Poubelles[]
     */
    public function getPoubelles(): Collection
    {
        return $this->poubelles;
    }

    public function addPoubelle(Poubelles $poubelle): self
    {
        if (!$this->poubelles->contains($poubelle)) {
            $this->poubelles[] = $poubelle;
            $poubelle->setCouleur($this);
        }

        return $this;
    }

    public function removePoubelle(Poubelles $poubelle): self
    {
        if ($this->poubelles->removeElement($poubelle)) {
            // set the owning side to null (unless already changed)
            if ($poubelle->getCouleur() === $this) {
                $poubelle->setCouleur(null);
            }
        }

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
}
