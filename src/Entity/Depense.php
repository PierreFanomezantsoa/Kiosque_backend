<?php

namespace App\Entity;

use App\Repository\DepenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepenseRepository::class)]
class Depense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $refDep = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDep = null;

    #[ORM\ManyToOne(inversedBy: 'depense')]
    private ?BudgetMensuel $budgetMensuel = null;

    #[ORM\ManyToOne(inversedBy: 'depense')]
    private ?Categorie $catId = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    private ?MembreFamille $membrefamille = null;

    #[ORM\Column(nullable: true)]
    private ?float $montantDepense = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefDep(): ?int
    {
        return $this->refDep;
    }

    public function setRefDep(int $refDep): static
    {
        $this->refDep = $refDep;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDep(): ?\DateTimeInterface
    {
        return $this->dateDep;
    }

    public function setDateDep(\DateTimeInterface $dateDep): static
    {
        $this->dateDep = $dateDep;

        return $this;
    }

    public function getBudgetMensuel(): ?BudgetMensuel
    {
        return $this->budgetMensuel;
    }

    public function setBudgetMensuel(?BudgetMensuel $budgetMensuel): static
    {
        $this->budgetMensuel = $budgetMensuel;

        return $this;
    }

    public function getCatId(): ?Categorie
    {
        return $this->catId;
    }

    public function setCatId(?Categorie $catId): static
    {
        $this->catId = $catId;

        return $this;
    }

    public function getMembrefamille(): ?MembreFamille
    {
        return $this->membrefamille;
    }

    public function setMembrefamille(?MembreFamille $membrefamille): static
    {
        $this->membrefamille = $membrefamille;

        return $this;
    }

    public function getMontantDepense(): ?float
    {
        return $this->montantDepense;
    }

    public function setMontantDepense(?float $montantDepense): static
    {
        $this->montantDepense = $montantDepense;

        return $this;
    }

}
