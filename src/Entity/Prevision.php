<?php

namespace App\Entity;

use App\Repository\PrevisionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrevisionRepository::class)]
class Prevision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $temperatureC = null;

    #[ORM\Column]
    private ?float $temperatureF = null;

    #[ORM\Column]
    private ?float $pression = null;

    #[ORM\Column]
    private ?float $humidite = null;

    #[ORM\Column]
    private ?float $luminosite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
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

    public function getTemperatureC(): ?float
    {
        return $this->temperatureC;
    }

    public function setTemperatureC(float $temperatureC): static
    {
        $this->temperatureC = $temperatureC;

        return $this;
    }

    public function getTemperatureF(): ?float
    {
        return $this->temperatureF;
    }

    public function setTemperatureF(float $temperatureF): static
    {
        $this->temperatureF = $temperatureF;

        return $this;
    }

    public function getPression(): ?float
    {
        return $this->pression;
    }

    public function setPression(float $pression): static
    {
        $this->pression = $pression;

        return $this;
    }

    public function getHumidite(): ?float
    {
        return $this->humidite;
    }

    public function setHumidite(float $humidite): static
    {
        $this->humidite = $humidite;

        return $this;
    }

    public function getLuminosite(): ?float
    {
        return $this->luminosite;
    }

    public function setLuminosite(float $luminosite): static
    {
        $this->luminosite = $luminosite;

        return $this;
    }
}
