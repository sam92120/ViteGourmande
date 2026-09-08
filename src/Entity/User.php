<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AccountStatusException;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column]
    private bool $isVerified = true;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'user', cascade: ['persist'])]
    private Collection $commandes;

   /**
 * @var Collection<int, Avis>
 */
#[ORM\OneToMany(
    targetEntity: Avis::class,
    mappedBy: 'user',
    cascade: ['persist', 'remove'],
    orphanRemoval: true
)]
private Collection $avis;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    //notification
    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'user', cascade: ['persist'])]
    private Collection $notifications;

    #[ORM\Column]
    private ?bool $isActive = true;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email; // retourne l'email de l'utilisateur
    }


    /**
     * @param string $email
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /** 
     * A visualiser pour identifier l'utilisateur.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email; // retourne l'identifiant de l'utilisateur
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // récupère les rôles de l'utilisateur
        $roles = $this->roles;
        // garantie que chaque utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';    // ajoute le rôle ROLE_USER à l'utilisateur   
        

        return array_unique($roles); // supprime les doublons
    }

    /**
     * @param list<string> $roles 
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password; // retourne le mot de passe de l'utilisateur
    }

    public function setPassword(string $password): static
    {
        $this->password = $password; // définit le mot de passe de l'utilisateur

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom; // retourne le prénom de l'utilisateur
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom; // définit le prénom de l'utilisateur

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone; // retourne le numéro de téléphone de l'utilisateur
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone; // définit le numéro de téléphone de l'utilisateur

        return $this;
    }

    public function getVile(): ?string
    {
        return $this->vile; // retourne la ville de l'utilisateur
    }

    public function setVile(?string $vile): static
    {
        $this->vile = $vile; // définit la ville de l'utilisateur

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays; // retourne le pays de l'utilisateur
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays; // définit le pays de l'utilisateur

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse; // retourne l'adresse de l'utilisateur
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse; // définit l'adresse de l'utilisateur

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified; // retourne l'état de vérification de l'utilisateur
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified; // définit l'état de vérification de l'utilisateur

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setUser($this); // définit l'utilisateur de la commande
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setUser($this); // définit l'utilisateur de l'avis
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom; // retourne le nom de l'utilisateur
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom; // définit le nom de l'utilisateur

        return $this;
    }

public function __toString()
{
    return $this->prenom . ' ' . $this->nom;
}

/**
 * @return Collection<int, Notification>
 */
public function getNotifications(): Collection
{
    return $this->notifications;
}

public function addNotification(Notification $notification): static
{
    if (!$this->notifications->contains($notification)) {
        $this->notifications->add($notification);
        $notification->setUser($this); // définit l'utilisateur de la notification
    }

    return $this;
}

public function removeNotification(Notification $notification): static
{
    if ($this->notifications->removeElement($notification)) {
        // set the owning side to null (unless already changed)
        if ($notification->getUser() === $this) {
            $notification->setUser(null); // supprime l'utilisateur de la notification
        }
    }

    return $this;
}

public function isActive(): ?bool
{
    return $this->isActive;
}

public function setIsActive(bool $isActive): static
{
    $this->isActive = $isActive; // définit l'état actif de l'utilisateur

    return $this;
}

 public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return $this->isActive;
    }

/**
 * @see UserInterface
 */
public function eraseCredentials(): void
{
    // Si tu stockes des données sensibles temporaires, efface-les ici
}

}