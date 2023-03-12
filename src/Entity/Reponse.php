<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $laReponse = null;

    #[ORM\ManyToOne(inversedBy: 'Reponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    #[ORM\ManyToMany(targetEntity: UserSondageReponse::class, mappedBy: 'reponses')]
    private Collection $userSondageReponses;

    public function __construct()
    {
        $this->userSondageReponses = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLaReponse(): ?string
    {
        return $this->laReponse;
    }

    public function setLaReponse(string $laReponse): self
    {
        $this->laReponse = $laReponse;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function __toString(): string
    {
        return $this->laReponse;
    }

    /**
     * @return Collection<int, UserSondageReponse>
     */
    public function getUserSondageReponses(): Collection
    {
        return $this->userSondageReponses;
    }

    public function addUserSondageReponse(UserSondageReponse $userSondageReponse): self
    {
        if (!$this->userSondageReponses->contains($userSondageReponse)) {
            $this->userSondageReponses->add($userSondageReponse);
            $userSondageReponse->addReponse($this);
        }

        return $this;
    }

    public function removeUserSondageReponse(UserSondageReponse $userSondageReponse): self
    {
        if ($this->userSondageReponses->removeElement($userSondageReponse)) {
            $userSondageReponse->removeReponse($this);
        }

        return $this;
    }




}
