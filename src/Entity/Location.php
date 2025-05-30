<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 20)]
    private string $city;

    private const ALLOWED_CITIES = ['Lyon', 'Paris', 'Marseille'];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        if (!in_array($city, self::ALLOWED_CITIES, true)) {
            throw new \InvalidArgumentException("Invalid city value: $city. Allowed values are: " . implode(', ', self::ALLOWED_CITIES));
        }

        $this->city = $city;
        return $this;
    }
}