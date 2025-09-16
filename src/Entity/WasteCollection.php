<?php

namespace App\Entity;

use App\Repository\WasteCollectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection; 

#[ApiResource(
    normalizationContext: ['groups' => ['wasteCollection:read']]
)]

#[ORM\Entity(repositoryClass: WasteCollectionRepository::class)]
class WasteCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'wasteCollections')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;


    #[ORM\OneToMany(targetEntity: WasteItem::class, mappedBy: 'wasteCollection', cascade: ['persist', 'remove'])]
    #[Groups(['wasteCollection:read'])]
    private Collection $wasteItems;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt; 


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable(); 
        $this->wasteItems = new ArrayCollection(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }


    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }


    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable 
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection<int, WasteItem> 
     */
    public function getWasteItems(): Collection
    {
        return $this->wasteItems;
    }

    public function addWasteItem(WasteItem $wasteItem): self
    {
        if (!$this->wasteItems->contains($wasteItem)) {
            $this->wasteItems->add($wasteItem);
            $wasteItem->setWasteCollection($this);
        }
        return $this;
    }

    public function removeWasteItem(WasteItem $wasteItem): self
    {
        if ($this->wasteItems->removeElement($wasteItem)) {
            if ($wasteItem->getWasteCollection() === $this) {
                $wasteItem->setWasteCollection(null);
            }
        }
        return $this;
    }
}