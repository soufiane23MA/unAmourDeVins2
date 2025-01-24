<?php

namespace App\Entity;

use App\Repository\DomaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DomaineRepository::class)]
class Domaine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomDomaine = null;

    #[ORM\Column(length: 100)]
    private ?string $adresseDomaine = null;

    #[ORM\ManyToOne(inversedBy: 'domaines')]
    private ?Region $region = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'domaine')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDomaine(): ?string
    {
        return $this->nomDomaine;
    }

    public function setNomDomaine(string $nomDomaine): static
    {
        $this->nomDomaine = $nomDomaine;

        return $this;
    }

    public function getAdresseDomaine(): ?string
    {
        return $this->adresseDomaine;
    }

    public function setAdresseDomaine(string $adresseDomaine): static
    {
        $this->adresseDomaine = $adresseDomaine;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setDomaine($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getDomaine() === $this) {
                $produit->setDomaine(null);
            }
        }

        return $this;
    }
}
