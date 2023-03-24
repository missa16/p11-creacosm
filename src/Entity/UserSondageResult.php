<?php

namespace App\Entity;

use App\Repository\UserSondageResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSondageResultRepository::class)]
class UserSondageResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(inversedBy: 'lesSondes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sondage $sondage = null;

    #[ORM\OneToMany(mappedBy: 'userSondageResult', targetEntity: UserSondageReponse::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $allReponses;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateReponse = null;

    #[ORM\ManyToOne(inversedBy: 'sondagesRepondus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sonde = null;

    public function __construct()
    {
        $this->allReponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getSondage(): ?Sondage
    {
        return $this->sondage;
    }

    public function setSondage(?Sondage $sondage): self
    {
        $this->sondage = $sondage;

        return $this;
    }

    /**
     * @return Collection<int, UserSondageReponse>
     */
    public function getAllReponses(): Collection
    {
        return $this->allReponses;
    }

    public function addAllReponse(UserSondageReponse $allReponse): self
    {
        if (!$this->allReponses->contains($allReponse)) {
            $this->allReponses->add($allReponse);
            $allReponse->setUserSondageResult($this);
        }

        return $this;
    }

    public function removeAllReponse(UserSondageReponse $allReponse): self
    {
        if ($this->allReponses->removeElement($allReponse)) {
            // set the owning side to null (unless already changed)
            if ($allReponse->getUserSondageResult() === $this) {
                $allReponse->setUserSondageResult(null);
            }
        }

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    public function getSonde(): ?User
    {
        return $this->sonde;
    }

    public function setSonde(?User $sonde): self
    {
        $this->sonde = $sonde;

        return $this;
    }
}
