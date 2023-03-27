<?php

namespace App\Entity;

use App\Repository\CategorieSondageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieSondageRepository::class)]
class
CategorieSondage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCategorie = null;

    #[ORM\OneToMany(mappedBy: 'categorieSondage', targetEntity: Sondage::class)]
    private Collection $Sondage;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emoji = null;

    public function __construct()
    {
        $this->Sondage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Sondage>
     */
    public function getSondage(): Collection
    {
        return $this->Sondage;
    }

    public function addSondage(Sondage $sondage): self
    {
        if (!$this->Sondage->contains($sondage)) {
            $this->Sondage->add($sondage);
            $sondage->setCategorieSondage($this);
        }

        return $this;
    }

    public function removeSondage(Sondage $sondage): self
    {
        if ($this->Sondage->removeElement($sondage)) {
            // set the owning side to null (unless already changed)
            if ($sondage->getCategorieSondage() === $this) {
                $sondage->setCategorieSondage(null);
            }
        }

        return $this;
    }

    public function getEmoji(): ?string
    {
        return $this->emoji;
    }

    public function setEmoji(?string $emoji): self
    {
        $this->emoji = $emoji;

        return $this;
    }
}
