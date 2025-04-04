<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    /**
     * définir une liste de statuts pour éviter les erreurs liées à la saisie manuelle 
     * et garantir une certaine cohérence
     */
    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TERMINEE = 'terminée';
    public const STATUT_ANNULEE = 'annulée';
    public const  STATUT_VALIDEE = 'validée';
    public const STATUT_PAYEE = 'payée';
    
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    /**
     * @var Collection<int, ProduitCommande>
     */
    #[ORM\OneToMany(targetEntity: ProduitCommande::class, mappedBy: 'commande')]
    private Collection $produitCommandes;

    //  je l'ai rajouter pour relier à la facture et générer getFactur()
    #[ORM\OneToOne(mappedBy: 'commande', targetEntity: Facture::class)]
    private ?Facture $facture = null;

// + Getters/Setters générés automatiquement

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;
    #[ORM\Column(type: 'string', length: 255)] // on rajoute cest attribut à la commande pour pourvoir la garder dans la BDD
    private ?string $userNom = null; 

    #[ORM\Column(type: 'string', length: 255)]// on rajoute cest attribut à la commande pour pourvoir la garder dans la BDD
    private ?string $userPrenom = null;
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $modeLivraison = null;
    #[ORM\Column(length: 255, unique : true)]
    private ?string $numeroCommande = null;
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montantTTC = null;

    public function __construct()
    {
        $this->produitCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

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
            $produitCommande->setCommande($this);
        }

        return $this;
    }

    public function removeProduitCommande(ProduitCommande $produitCommande): static
    {
        if ($this->produitCommandes->removeElement($produitCommande)) {
            // set the owning side to null (unless already changed)
            if ($produitCommande->getCommande() === $this) {
                $produitCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        if (!in_array($statut, [self::STATUT_EN_COURS, self::STATUT_TERMINEE, self::STATUT_ANNULEE,self::STATUT_VALIDEE,self::STATUT_PAYEE])) {
            throw new \InvalidArgumentException("Statut invalide");
        }
        $this->statut = $statut;

        return $this;
    }
    
    public function getTotal(): float
{
    $total = 0;

    // Supposons que tu as une relation 'produitCommandes' qui représente les produits de la commande
    foreach ($this->produitCommandes as $produitCommande) {
        $total += $produitCommande->getProduit()->getPrix() * $produitCommande->getQuantite();
    }

    return $total;
}
// rajou de la methode qui recupere le mode de livraison

public function getModeLivraison(): ?string
{
    return $this->modeLivraison;
}

public function setModeLivraison(string $modeLivraison): self
{
    $this->modeLivraison = $modeLivraison;
    return $this;
}

public function getNumeroCommande(): ?string
{
    return $this->numeroCommande;
}

public function setNumeroCommande(string $numeroCommande): static
{
    $this->numeroCommande = $numeroCommande;

    return $this;
}

/**
 * Get the value of userNom
 */ 
public function getUserNom()
{
    return $this->userNom;
}

/**
 * Set the value of userNom
 *
 * @return  self
 */ 
public function setUserNom($userNom)
{
    $this->userNom = $userNom;

    return $this;
}

/**
 * Get the value of userPrenom
 */ 
public function getUserPrenom()
{
    return $this->userPrenom;
}

/**
 * Set the value of userPrenom
 *
 * @return  self
 */ 
public function setUserPrenom($userPrenom)
{
    $this->userPrenom = $userPrenom;

    return $this;
}

public function __tostring(){
    return $this->getNumeroCommande().$this->getDateCommande()
        .$this->getUserNom().$this->getUserPrenom();
}

/**
 * Get the value of facture
 */ 
public function getFacture()
{
    return $this->facture;
}

/**
 * Set the value of facture
 *
 * @return  self
 */ 
public function setFacture($facture)
{
    $this->facture = $facture;

    return $this;
}

public function getMontantTTC(): ?string
{
    return $this->montantTTC;
}

public function setMontantTTC(string $montantTTC): static
{
    $this->montantTTC = $montantTTC;

    return $this;
}
}
