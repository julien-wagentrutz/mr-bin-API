<?php

namespace App\Entity;

use App\Repository\ContenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContenuRepository::class)
 */
class Contenu
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
     * @ORM\Column(type="string", length=255)
     * @Groups("horaires")
     */
    private $icon;

    /**
     * @ORM\ManyToMany(targetEntity=Poubelles::class, mappedBy="contenues")
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

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
            $poubelle->addContenue($this);
        }

        return $this;
    }

    public function removePoubelle(Poubelles $poubelle): self
    {
        if ($this->poubelles->removeElement($poubelle)) {
            $poubelle->removeContenue($this);
        }

        return $this;
    }
}
