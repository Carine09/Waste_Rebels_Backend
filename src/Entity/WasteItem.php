<?php

namespace App\Entity;

use App\Repository\WasteItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WasteItemRepository::class)]
class WasteItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: WasteCollection::class, inversedBy: 'wasteItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WasteCollection $wasteCollection = null;

    #[ORM\ManyToOne(targetEntity: WasteType::class, inversedBy: 'wasteItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WasteType $wasteType = null;

    #[ORM\Column(type: Types::FLOAT, nullable: false)]
    private float $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getWasteCollection(): ?WasteCollection
    {
        return $this->wasteCollection;
    }

    public function setWasteCollection(WasteCollection $wasteCollection): self
    {
        $this->wasteCollection = $wasteCollection;
        return $this;
    }

    public function getWasteType(): ?WasteType
    {
        return $this->wasteType;
    }

    public function setWasteType(WasteType $wasteType): self
    {
        $this->wasteType = $wasteType;
        return $this;
    }
}