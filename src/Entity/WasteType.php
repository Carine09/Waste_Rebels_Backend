<?php

namespace App\Entity;

use App\Repository\WasteTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WasteTypeRepository::class)]
class WasteType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 30)]
    private string $value;

    #[ORM\Column(type: "string", length: 30)]
    private string $label;

    private const ALLOWED_VALUES = [
        'cigarettes',
        'plastic',
        'glass',
        'electronic_waste',
        'metal_waste',
        'others',
    ];

    private const ALLOWED_LABELS = [
        'Cigarette butts',
        'Plastic waste',
        'Glass waste',
        'Electronic waste',
        'Metal waste',
        'Others',
    ];

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
        if (!in_array($value, self::ALLOWED_VALUES, true)) {
            throw new \InvalidArgumentException(
                "Invalid value: $value. Allowed values are: " . implode(', ', self::ALLOWED_VALUES)
            );
        }
        $this->value = $value;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        if (!in_array($label, self::ALLOWED_LABELS, true)) {
            throw new \InvalidArgumentException(
                "Invalid label: $label. Allowed labels are: " . implode(', ', self::ALLOWED_LABELS)
            );
        }
        $this->label = $label;
        return $this;
    }
}