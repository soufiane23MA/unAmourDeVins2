<?php

namespace App\Entity;

use App\Repository\AccordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccordRepository::class)]
class Accord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'accords')]
    #[ORM\JoinColumn(name: "plat_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Plat $plat = null;

    #[ORM\ManyToOne(inversedBy: 'accords')]
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(?Plat $plat): static
    {
        $this->plat = $plat;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
