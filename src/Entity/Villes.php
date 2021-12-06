<?php

namespace App\Entity;

use App\Repository\VillesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VillesRepository::class)
 */
class Villes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Poubelles::class, mappedBy="ville")
     */
    private $poubelles;

    public function __construct()
    {
        $this->poubelles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
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
            $poubelle->setVille($this);
        }

        return $this;
    }

    public function removePoubelle(Poubelles $poubelle): self
    {
        if ($this->poubelles->removeElement($poubelle)) {
            // set the owning side to null (unless already changed)
            if ($poubelle->getVille() === $this) {
                $poubelle->setVille(null);
            }
        }

        return $this;
    }
}
