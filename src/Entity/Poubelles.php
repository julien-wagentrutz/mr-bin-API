<?php

namespace App\Entity;

use App\Repository\PoubellesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PoubellesRepository::class)
 */
class Poubelles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"horaires"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Contenu::class, inversedBy="poubelles")
     * @Groups("horaires")
     */
    private $contenue;

    /**
     * @ORM\ManyToOne(targetEntity=Couleurs::class, inversedBy="poubelles")
     * @Groups("horaires")
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity=Villes::class, inversedBy="poubelles")
     */
    private $ville;

    /**
     * @Groups("fgd")
     * @ORM\OneToMany(targetEntity=Horaires::class, mappedBy="poubelles")
     */
    private $horaires;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenue(): ?Contenu
    {
        return $this->contenue;
    }

    public function setContenue(?Contenu $contenue): self
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getCouleur(): ?Couleurs
    {
        return $this->couleur;
    }

    public function setCouleur(?Couleurs $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Horaires[]
     */
    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    public function addHoraire(Horaires $horaire): self
    {
        if (!$this->horaires->contains($horaire)) {
            $this->horaires[] = $horaire;
            $horaire->setPoubelles($this);
        }

        return $this;
    }

    public function removeHoraire(Horaires $horaire): self
    {
        if ($this->horaires->removeElement($horaire)) {
            // set the owning side to null (unless already changed)
            if ($horaire->getPoubelles() === $this) {
                $horaire->setPoubelles(null);
            }
        }

        return $this;
    }
}
