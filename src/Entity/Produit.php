<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomProduit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $detailProduit = null;

    #[ORM\Column(length: 50)]
    private ?string $contenance = null;

    #[ORM\Column]
    private ?bool $exclusif = null;

    /**
     * @var Collection<int, ProduitCommande>
     */
    #[ORM\OneToMany(targetEntity: ProduitCommande::class, mappedBy: 'produit')]
    private Collection $produitCommandes;

    /**
     * @var Collection<int, Accord>
     */
    #[ORM\OneToMany(targetEntity: Accord::class, mappedBy: 'produit')]
    private Collection $accords;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Domaine $domaine = null;

    public function __construct()
    {
        $this->produitCommandes = new ArrayCollection();
        $this->accords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): static
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDetailProduit(): ?string
    {
        return $this->detailProduit;
    }

    public function setDetailProduit(string $detailProduit): static
    {
        $this->detailProduit = $detailProduit;

        return $this;
    }

    public function getContenance(): ?string
    {
        return $this->contenance;
    }

    public function setContenance(string $contenance): static
    {
        $this->contenance = $contenance;

        return $this;
    }

    public function isExclusif(): ?bool
    {
        return $this->exclusif;
    }

    public function setExclusif(bool $exclusif): static
    {
        $this->exclusif = $exclusif;

        return $this;
    }

    /**
     * @return Collection<int, ProduitCommande>
     */
    public function getProduitCommandes(): Collection
    {
        return $this->produitCommandes;
    }

    public function addProduitCommande(ProduitCommande $produitCommande): static
    {
        if (!$this->produitCommandes->contains($produitCommande)) {
            $this->produitCommandes->add($produitCommande);
            $produitCommande->setProduit($this);
        }

        return $this;
    }

    public function removeProduitCommande(ProduitCommande $produitCommande): static
    {
        if ($this->produitCommandes->removeElement($produitCommande)) {
            // set the owning side to null (unless already changed)
            if ($produitCommande->getProduit() === $this) {
                $produitCommande->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Accord>
     */
    public function getAccords(): Collection
    {
        return $this->accords;
    }

    public function addAccord(Accord $accord): static
    {
        if (!$this->accords->contains($accord)) {
            $this->accords->add($accord);
            $accord->setProduit($this);
        }

        return $this;
    }

    public function removeAccord(Accord $accord): static
    {
        if ($this->accords->removeElement($accord)) {
            // set the owning side to null (unless already changed)
            if ($accord->getProduit() === $this) {
                $accord->setProduit(null);
            }
        }

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }
}
