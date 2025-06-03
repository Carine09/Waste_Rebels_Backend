<?php

namespace App\Entity;

use App\Repository\WasteCollectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WasteCollectionRepository::class)]
class WasteCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(type: Types::BIGINT)]
    // private ?string $user_id = null;

    // #[ORM\Column(type: Types::BIGINT)]
    // private ?string $location_id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'waste_collections')]
    private User $user_id;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'waste_collections')]
    private Location $location_id;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getUserId(): ?string
    // {
    //     return $this->user_id;
    // }

    // public function setUserId(string $user_id): static
    // {
    //     $this->user_id = $user_id;

    //     return $this;
    // }

    // public function getLocationId(): ?string
    // {
    //     return $this->location_id;
    // }

    // public function setLocationId(string $location_id): static
    // {
    //     $this->location_id = $location_id;

    //     return $this;
    // }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user = $user_id;

        return $this;
    }

    public function getLocationId(): ?Location
    {
        return $this->location_id;
    }

    public function setLocationId(?Location $location_id): self
    {
        $this->location = $location_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
