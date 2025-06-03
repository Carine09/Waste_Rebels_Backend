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
        $this->value = $value;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {

        $this->label = $label;
        return $this;
    }
}