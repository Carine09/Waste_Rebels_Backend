<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: "string", length: 20)]
    private string $role;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Location $location = null;

    #[ORM\OneToMany(targetEntity: WasteCollection::class, mappedBy: 'user')]
    private Collection $wasteCollections;

    private const ALLOWED_ROLES = ['Admin', 'Volunteer'];

    public function __construct()
    {
        $this->wasteCollections = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->is_active = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(?bool $is_active): static
    {
        $this->is_active = $is_active;
        $this->updated_at = new \DateTimeImmutable();
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getRoles(): array
{
    return match ($this->role) {
        'Admin' => ['ROLE_ADMIN'],
        'Volunteer' => ['ROLE_VOLUNTEER'],
        default => ['ROLE_USER'],
    };
}

    public function setRole(string $role): self
    {
        if (!in_array($role, self::ALLOWED_ROLES)) {
            throw new \InvalidArgumentException('Invalid role. Allowed roles are: ' . implode(', ', self::ALLOWED_ROLES));
        }
        $this->role = $role;
        $this->updated_at = new \DateTimeImmutable();
        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;
        $this->updated_at = new \DateTimeImmutable();
        return $this;
    }

    /**
     * @return Collection<int, WasteCollection>
     */
    public function getWasteCollections(): Collection
    {
        return $this->wasteCollections;
    }

    public function addWasteCollection(WasteCollection $wasteCollection): self
    {
        if (!$this->wasteCollections->contains($wasteCollection)) {
            $this->wasteCollections[] = $wasteCollection;
            $wasteCollection->setUser($this);
        }
        return $this;
    }

    public function removeWasteCollection(WasteCollection $wasteCollection): self
    {
        if ($this->wasteCollections->removeElement($wasteCollection)) {
            if ($wasteCollection->getUser() === $this) {
                $wasteCollection->setUser(null);
            }
        }
        return $this;
    }

    public static function getAllowedRoles(): array
    {
        return self::ALLOWED_ROLES;
    }

    public function getFullName(): string
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }


    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles en clair (ex: plainPassword), nettoie-les ici
    }
}