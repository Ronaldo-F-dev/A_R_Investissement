<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Table(name:"plans")]
#[ORM\Entity(repositoryClass: PlanRepository::class)]
class Plan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montantMin = null;

    #[ORM\Column]
    private ?float $montantMax = null;

    #[ORM\Column]
    private ?float $gain = null;

    #[ORM\Column]
    private ?int $duree = null;

    /**
     * @var Collection<int, Investissement>
     */
    #[ORM\OneToMany(targetEntity: Investissement::class, mappedBy: 'plan', orphanRemoval: true)]
    private Collection $investissements;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function __construct()
    {
        $this->investissements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantMin(): ?float
    {
        return $this->montantMin;
    }

    public function setMontantMin(float $montantMin): static
    {
        $this->montantMin = $montantMin;

        return $this;
    }

    public function getMontantMax(): ?float
    {
        return $this->montantMax;
    }

    public function setMontantMax(float $montantMax): static
    {
        $this->montantMax = $montantMax;

        return $this;
    }

    public function getGain(): ?float
    {
        return $this->gain;
    }

    public function setGain(float $gain): static
    {
        $this->gain = $gain;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection<int, Investissement>
     */
    public function getInvestissements(): Collection
    {
        return $this->investissements;
    }

    public function addInvestissement(Investissement $investissement): static
    {
        if (!$this->investissements->contains($investissement)) {
            $this->investissements->add($investissement);
            $investissement->setPlan($this);
        }

        return $this;
    }

    public function removeInvestissement(Investissement $investissement): static
    {
        if ($this->investissements->removeElement($investissement)) {
            // set the owning side to null (unless already changed)
            if ($investissement->getPlan() === $this) {
                $investissement->setPlan(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
