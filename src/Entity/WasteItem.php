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

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $waste_type_id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $waste_collection_id = null;

    #[ORM\Column]
    private ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWasteTypeId(): ?string
    {
        return $this->waste_type_id;
    }

    public function setWasteTypeId(string $waste_type_id): static
    {
        $this->waste_type_id = $waste_type_id;

        return $this;
    }

    public function getWasteCollectionId(): ?string
    {
        return $this->waste_collection_id;
    }

    public function setWasteCollectionId(string $waste_collection_id): static
    {
        $this->waste_collection_id = $waste_collection_id;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
