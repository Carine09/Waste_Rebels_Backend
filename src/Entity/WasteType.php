<?php

namespace App\Entity;

use App\Repository\WasteTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: WasteTypeRepository::class)]
class WasteType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 30, nullable: false)]
    private string $value;

    #[ORM\OneToMany(targetEntity: WasteItem::class, mappedBy: 'wasteType')]
    private Collection $wasteItems;

    private const ALLOWED_VALUES = [
        'cigarettes',
        'plastic',
        'glass',
        'electronic_waste',
        'metal_waste',
        'others',
    ];

    public function __construct()
    {
        $this->wasteItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        if (!in_array($value, self::ALLOWED_VALUES)) {
            throw new \InvalidArgumentException('Invalid value. Allowed values are: ' . implode(', ', self::ALLOWED_VALUES));
        }
        $this->value = $value;
        return $this;
    }

    /**
     * @return Collection<int, WasteItem>
     */
    public function getWasteItems(): Collection
    {
        return $this->wasteItems;
    }
}