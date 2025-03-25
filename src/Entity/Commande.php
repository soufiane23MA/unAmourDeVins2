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

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

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
#[ORM\Column(type: 'string', length: 50, nullable: true)]
private ?string $modeLivraison = null;

public function getModeLivraison(): ?string
{
    return $this->modeLivraison;
}

public function setModeLivraison(string $modeLivraison): self
{
    $this->modeLivraison = $modeLivraison;
    return $this;
}
}
