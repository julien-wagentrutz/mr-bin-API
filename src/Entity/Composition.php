<?php

namespace App\Entity;

use App\Repository\CompositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompositionRepository::class)
 */
class Composition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("produit")
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Contenu::class, inversedBy="compositions")
     * @Groups("produit")
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="Compositions")
     */
    private $produit;

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

    public function getMatiere(): ?Contenu
    {
        return $this->matiere;
    }

    public function setMatiere(?Contenu $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}
