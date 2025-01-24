<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomPlat = null;

    /**
     * @var Collection<int, Accord>
     */
    #[ORM\OneToMany(targetEntity: Accord::class, mappedBy: 'plat')]
    private Collection $accords;

    public function __construct()
    {
        $this->accords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlat(): ?string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): static
    {
        $this->nomPlat = $nomPlat;

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
            $accord->setPlat($this);
        }

        return $this;
    }

    public function removeAccord(Accord $accord): static
    {
        if ($this->accords->removeElement($accord)) {
            // set the owning side to null (unless already changed)
            if ($accord->getPlat() === $this) {
                $accord->setPlat(null);
            }
        }

        return $this;
    }
}
