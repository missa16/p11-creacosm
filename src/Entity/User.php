<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $genre = null;



    #[ORM\OneToMany(mappedBy: 'sondeur', targetEntity: Sondage::class, orphanRemoval: true)]
    private Collection $sondageCrees;



    #[ORM\ManyToOne(inversedBy: 'Inscrit')]
    private ?Formation $formation = null;

    #[ORM\OneToMany(mappedBy: 'sonde', targetEntity: UserSondageResult::class, orphanRemoval: true)]
    private Collection $sondagesRepondus;

    public function __construct()
    {
        $this->sondageCrees = new ArrayCollection();
        $this->sondagesRepondus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }



    /**
     * @return Collection<int, Sondage>
     */
    public function getSondageCrees(): Collection
    {
        return $this->sondageCrees;
    }

    public function addSondageCree(Sondage $sondageCree): self
    {
        if (!$this->sondageCrees->contains($sondageCree)) {
            $this->sondageCrees->add($sondageCree);
            $sondageCree->setSondeur($this);
        }

        return $this;
    }

    public function removeSondageCree(Sondage $sondageCree): self
    {
        if ($this->sondageCrees->removeElement($sondageCree)) {
            // set the owning side to null (unless already changed)
            if ($sondageCree->getSondeur() === $this) {
                $sondageCree->setSondeur(null);
            }
        }

        return $this;
    }




    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, UserSondageResult>
     */
    public function getSondagesRepondus(): Collection
    {
        return $this->sondagesRepondus;
    }

    public function addSondagesRepondu(UserSondageResult $sondagesRepondu): self
    {
        if (!$this->sondagesRepondus->contains($sondagesRepondu)) {
            $this->sondagesRepondus->add($sondagesRepondu);
            $sondagesRepondu->setSonde($this);
        }

        return $this;
    }

    public function removeSondagesRepondu(UserSondageResult $sondagesRepondu): self
    {
        if ($this->sondagesRepondus->removeElement($sondagesRepondu)) {
            // set the owning side to null (unless already changed)
            if ($sondagesRepondu->getSonde() === $this) {
                $sondagesRepondu->setSonde(null);
            }
        }

        return $this;
    }
}
