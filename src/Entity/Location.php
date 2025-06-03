<?php
namespace App\Entity;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'location')]
    private Collection $users;
    
    #[ORM\OneToMany(targetEntity: WasteCollection::class, mappedBy: 'location')]
    private Collection $wasteCollections;
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->wasteCollections = new ArrayCollection();
    }
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
        $this->city = $city;
        return $this;
    }
    public function getUsers(): Collection
    {
        return $this->users;
    }
    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setLocation($this);
        }
        return $this;
    }
    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            if ($user->getLocation() === $this) {
                $user->setLocation(null);
            }
        }
        return $this;
    }

    public function getWasteCollections(): Collection
    {
        return $this->wasteCollections;
    }
    public function addWasteCollection(WasteCollection $wasteCollection): self
    {
        if (!$this->wasteCollections->contains($wasteCollection)) {
            $this->wasteCollections[] = $wasteCollection;
            $wasteCollection->setLocation($this);
        }
        return $this;
    }
    public function removeWasteCollection(WasteCollection $wasteCollection): self
    {
        if ($this->wasteCollections->removeElement($wasteCollection)) {
            if ($wasteCollection->getLocation() === $this) {
                $wasteCollection->setLocation(null);
            }
        }
        return $this;
    }
}