<?php

namespace App\Entity;

use App\Repository\RevenueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevenueRepository::class)]
class Revenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRevenue = null;

    #[ORM\Column(length: 150)]
    private ?string $descrptionRev = null;

    #[ORM\Column(length: 100)]
    private ?string $source = null;

    #[ORM\ManyToOne(inversedBy: 'revenues')]
    private ?BudgetMensuel $budgeMensuel = null;

    #[ORM\ManyToOne(inversedBy: 'revenues')]
    private ?MembreFamille $membreFamille = null;

    #[ORM\ManyToOne(inversedBy: 'revenues')]
    private ?Categorie $cat = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantRevenue = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRevenue(): ?\DateTimeInterface
    {
        return $this->dateRevenue;
    }

    public function setDateRevenue(\DateTimeInterface $dateRevenue): static
    {
        $this->dateRevenue = $dateRevenue;

        return $this;
    }

    public function getDescrptionRev(): ?string
    {
        return $this->descrptionRev;
    }

    public function setDescrptionRev(string $descrptionRev): static
    {
        $this->descrptionRev = $descrptionRev;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getBudgeMensuel(): ?BudgetMensuel
    {
        return $this->budgeMensuel;
    }

    public function setBudgeMensuel(?BudgetMensuel $budgeMensuel): static
    {
        $this->budgeMensuel = $budgeMensuel;

        return $this;
    }

    public function getMembreFamille(): ?MembreFamille
    {
        return $this->membreFamille;
    }

    public function setMembreFamille(?MembreFamille $membreFamille): static
    {
        $this->membreFamille = $membreFamille;

        return $this;
    }

    public function getCat(): ?Categorie
    {
        return $this->cat;
    }

    public function setCat(?Categorie $cat): static
    {
        $this->cat = $cat;

        return $this;
    }

    public function getMontantRevenue(): ?float
    {
        return $this->montantRevenue;
    }

    public function setMontantRevenue(?float $montantRevenue): static
    {
        $this->montantRevenue = $montantRevenue;

        return $this;
    }
}
