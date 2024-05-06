<?php

namespace App\Entity;

use App\Repository\AlarmeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlarmeRepository::class)]
class Alarme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $valeur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inf = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sup = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function isInf(): ?bool
    {
        return $this->inf;
    }

    public function setInf(?bool $inf): static
    {
        $this->inf = $inf;

        return $this;
    }

    public function isSup(): ?bool
    {
        return $this->sup;
    }

    public function setSup(?bool $sup): static
    {
        $this->sup = $sup;

        return $this;
    }
}
