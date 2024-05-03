<?php

namespace App\Entity;

use App\Repository\MesuresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MesuresRepository::class)]
class Mesures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $vent = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperatureC = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperatureF = null;

    #[ORM\Column(nullable: true)]
    private ?float $humidite = null;

    #[ORM\Column(nullable: true)]
    private ?float $luminosite = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $orientation = null;

    #[ORM\Column(nullable: true)]
    private ?float $pression = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $dateHeure = null;

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(?\DateTimeInterface $dateHeure): void
    {
        $this->dateHeure = $dateHeure;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(float $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getVent(): ?float
    {
        return $this->vent;
    }

    public function setVent(?float $vent): static
    {
        $this->vent = $vent;

        return $this;
    }

    public function getTemperatureC(): ?float
    {
        return $this->temperatureC;
    }

    public function setTemperatureC(?float $temperatureC): static
    {
        $this->temperatureC = $temperatureC;

        return $this;
    }

    public function getTemperatureF(): ?float
    {
        return $this->temperatureF;
    }

    public function setTemperatureF(?float $temperatureF): static
    {
        $this->temperatureF = $temperatureF;

        return $this;
    }

    public function getHumidite(): ?float
    {
        return $this->humidite;
    }

    public function setHumidite(?float $humidite): static
    {
        $this->humidite = $humidite;

        return $this;
    }

    public function getLuminosite(): ?float
    {
        return $this->luminosite;
    }

    public function setLuminosite(?float $luminosite): static
    {
        $this->luminosite = $luminosite;

        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(?string $orientation): static
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getPression(): ?float
    {
        return $this->pression;
    }

    public function setPression(?float $pression): static
    {
        $this->pression = $pression;

        return $this;
    }
}
