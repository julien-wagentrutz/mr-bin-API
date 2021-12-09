<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("produit")
     */
    private $id;

	/**
	 * @ORM\Column(type="string", length=13, nullable=true)
	 * @Groups({"produit"})
	 */
	private $codeBarre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("produit")
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("produit")
     */
    private $marque;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @Groups({"produit"})
	 */
	private $SaviezVous;

	/**
	 * @Groups("produit")
	 */
	private $poubelles;

    /**
     * @ORM\OneToMany(targetEntity=Composition::class, mappedBy="produit")
     */
    private $Compositions;





    public function __construct()
    {
        $this->Compositions = new ArrayCollection();
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function createPoubelles()
    {
	    $this->poubelles = new ArrayCollection();
    }

	/**
	 * @return Collection|Composition[]
	 */
	public function getPoubelles(): Collection
	{
		return $this->poubelles;
	}

	public function addPoubelles(Poubelles $poubelles): self
	{
		if (!$this->poubelles->contains($poubelles)) {
			$this->poubelles[] = $poubelles;
		}

		return $this;
	}

	public function includePoubelle($collection, $poubelle)
	{
		$res = false;
		if($collection != null)
		{
			for($i = 0; $i<sizeof($collection) && !$res; $i++)
			{

				if($collection[$i] == $poubelle)
				{
					$res = true;
				}
			}
		}

		return $res;
	}


	/**
     * @return Collection|Composition[]
     */
    public function getCompositions(): Collection
    {
        return $this->Compositions;
    }

    public function addComposition(Composition $composition): self
    {
        if (!$this->Compositions->contains($composition)) {
            $this->Compositions[] = $composition;
            $composition->setProduit($this);
        }

        return $this;
    }

    public function removeComposition(Composition $composition): self
    {
        if ($this->Compositions->removeElement($composition)) {
            // set the owning side to null (unless already changed)
            if ($composition->getProduit() === $this) {
                $composition->setProduit(null);
            }
        }

        return $this;
    }

    public function getSaviezVous(): ?string
    {
        return $this->SaviezVous;
    }

    public function setSaviezVous(?string $SaviezVous): self
    {
        $this->SaviezVous = $SaviezVous;

        return $this;
    }

    public function getCodeBarre(): ?string
    {
        return $this->codeBarre;
    }

    public function setCodeBarre(?string $codeBarre): self
    {
        $this->codeBarre = $codeBarre;

        return $this;
    }
}
