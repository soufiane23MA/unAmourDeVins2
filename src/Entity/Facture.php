<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFacture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $totalFacture = null;
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $tva = null;  
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $montantHT = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)] 
    private ?Commande $commande = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    private ?string $numeroFacture = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $pdfPath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTimeInterface $dateFacture): static
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getTotalFacture(): ?float
    {
        return $this->totalFacture;
    }
    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): static
    {
        $this->tva = $tva;
        return $this;
    }
    public function getMontantHT(): ?float
    {
        return $this->montantHT;
    }

    public function setMontantHT(float $montantHT): static
    {
        $this->montantHT = $montantHT;
        return $this;
    }

    public function setTotalFacture(float $totalFacture): static
    
    // attention quand tu génére la facture avec TTC il nefaut pas confondre TotalFacture avec Total TTC
    {
        $this->totalFacture = $totalFacture;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get the value of numeroFacture
     */ 
    public function getNumeroFacture()
    {
        return $this->numeroFacture;
    }

    /**
     * Set the value of numeroFacture
     *
     * @return  self
     */ 
    public function setNumeroFacture($numeroFacture)
    {
        $this->numeroFacture = $numeroFacture;

        return $this;
    }
    // creer la pdfPath ici 

    /**
     * Get the value of pdfPath
     */ 
    public function getPdfPath()
    {
        return $this->pdfPath;
    }
    /**
     * Set the value of pdfPath
     *
     * @return  self
     */ 
    public function setPdfPath( string $pdfPath)
    {
        $this->pdfPath = $pdfPath;

        return $this;
    }
}
