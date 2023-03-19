<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomFormation = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: User::class)]
    private Collection $Inscrit;

    public function __construct()
    {
        $this->Inscrit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->nomFormation;
    }

    public function setNomFormation(string $nomFormation): self
    {
        $this->nomFormation = $nomFormation;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getInscrit(): Collection
    {
        return $this->Inscrit;
    }

    public function addInscrit(User $inscrit): self
    {
        if (!$this->Inscrit->contains($inscrit)) {
            $this->Inscrit->add($inscrit);
            $inscrit->setFormation($this);
        }

        return $this;
    }

    public function removeInscrit(User $inscrit): self
    {
        if ($this->Inscrit->removeElement($inscrit)) {
            // set the owning side to null (unless already changed)
            if ($inscrit->getFormation() === $this) {
                $inscrit->setFormation(null);
            }
        }

        return $this;
    }
}
