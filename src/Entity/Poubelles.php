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
     * @ORM\ManyToOne(targetEntity=Couleurs::class, inversedBy="poubelles")
     * @Groups({"horaires","produit","ville"})
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity=Villes::class, inversedBy="poubelles")
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Horaires::class, mappedBy="poubelles")
     */
    private $horaires;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 * @Groups({"horaires","ville", "produit"})
	 */
	private $recyclable;

	/**
	 * @Groups({"produit"})
	 */
    private $dechets;

    /**
     * @ORM\ManyToMany(targetEntity=Contenu::class, inversedBy="poubelles")
     * @Groups({"horaires","ville"})
     */
    private $contenues;



    public function __construct()
    {
        $this->horaires = new ArrayCollection();
        $this->contenues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * @return Collection|Horaires[]
	 */
	public function getDechets(): Collection
         	{
         		return $this->dechets;
         	}

	public function addDechets(Composition $composition): self
         	{
         		if (!$this->dechets->contains($composition)) {
         			$this->dechets[] = $composition;
         		}
         
         		return $this;
         	}

	public function createDechets()
         	{
         		$this->dechets = new ArrayCollection();
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

    /**
     * @return Collection|Contenu[]
     */
    public function getContenues(): Collection
    {
        return $this->contenues;
    }

    public function addContenue(Contenu $contenue): self
    {
        if (!$this->contenues->contains($contenue)) {
            $this->contenues[] = $contenue;
        }

        return $this;
    }

    public function removeContenue(Contenu $contenue): self
    {
        $this->contenues->removeElement($contenue);

        return $this;
    }

    public function getRecyclable(): ?string
    {
        return $this->recyclable;
    }

    public function setRecyclable(?string $recyclable): self
    {
        $this->recyclable = $recyclable;

        return $this;
    }
}
