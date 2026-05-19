<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Time;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $numeroCommande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateCommande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $datePretation = null;


    private ?float $prixMenu = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPers = null;

    #[ORM\Column(nullable: true)]
    private ?float $prixLivraison = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private mixed $pretMateriel = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private mixed $restiMateriel = null;

    #[ORM\ManyToOne(inversedBy: 'commandes', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $menu = null;

    #[ORM\ManyToOne(inversedBy: 'commandes', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Plat $platPrincipal = null;

    #[ORM\ManyToOne]
    private ?Plat $entree = null;

    #[ORM\ManyToOne]
    private ?Plat $dessert = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'commande',cascade: ['persist'])]
    private Collection $notifications;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $modeContactAnnulation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motifAnnulation = null;

    #[ORM\Column(options: ['default' => false])]
     private ?bool $materielPrete = false;


    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $heure_livraison = null;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCommande(): ?int
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(?int $numeroCommande): static
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->dateCommande;
    }

    public function setDateCommande(?\DateTime $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDatePretation(): ?\DateTime
    {
        return $this->datePretation;
    }

    public function setDatePretation(?\DateTime $datePretation): static
    {
        $this->datePretation = $datePretation;

        return $this;
    }

  
  

    public function getPrixMenu(): ?float
    {
        return $this->prixMenu;
    }

    public function setPrixMenu(?float $prixMenu): static
    {
        $this->prixMenu = $prixMenu;

        return $this;
    }

    public function getNbPers(): ?int
    {
        return $this->nbPers;
    }

    public function setNbPers(?int $nbPers): static
    {
        $this->nbPers = $nbPers;

        return $this;
    }

    public function getPrixLivraison(): ?float
    {
        return $this->prixLivraison;
    }

    public function setPrixLivraison(?float $prixLivraison): static
    {
        $this->prixLivraison = $prixLivraison;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPretMateriel(): mixed
    {
        return $this->pretMateriel;
    }

    public function setPretMateriel(mixed $pretMateriel): static
    {
        $this->pretMateriel = $pretMateriel;

        return $this;
    }

    public function getRestiMateriel(): mixed
    {
        return $this->restiMateriel;
    }

    public function setRestiMateriel(mixed $restiMateriel): static
    {
        $this->restiMateriel = $restiMateriel;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPlatPrincipal(): ?Plat
    {
        return $this->platPrincipal;
    }

    public function setPlatPrincipal(?Plat $platPrincipal): static
    {
        $this->platPrincipal = $platPrincipal;

        return $this;
    }

    public function getEntree(): ?Plat
    {
        return $this->entree;
    }

    public function setEntree(?Plat $entree): static
    {
        $this->entree = $entree;

        return $this;
    }

    public function getDessert(): ?Plat
    {
        return $this->dessert;
    }

    public function setDessert(?Plat $dessert): static
    {
        $this->dessert = $dessert;

        return $this;
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
            $notification->setCommande($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getCommande() === $this) {
                $notification->setCommande(null);
            }
        }

        return $this;
    }

    public function getModeContactAnnulation(): ?string
    {
        return $this->modeContactAnnulation;
    }

    public function setModeContactAnnulation(?string $modeContactAnnulation): static
    {
        $this->modeContactAnnulation = $modeContactAnnulation;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): static
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }

    public function isMaterielPrete(): ?bool
    {
        return $this->materielPrete;
    }

    public function setMaterielPrete(bool $materielPrete): static
    {
        $this->materielPrete = $materielPrete;

        return $this;
    }

    public function getHeureLivraison(): ?\DateTime
    {
        return $this->heure_livraison;
    }

    public function setHeureLivraison(?\DateTime $heure_livraison): static
    {
        $this->heure_livraison = $heure_livraison;

        return $this;
    }
}