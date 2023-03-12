<?php

namespace App\Entity;

use App\Repository\UserSondageReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSondageReponseRepository::class)]
class UserSondageReponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSondageReponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToMany(targetEntity: Reponse::class, inversedBy: 'userSondageReponses',cascade:['persist'])] //,cascade:['persist']
    private Collection $reponses;

    #[ORM\ManyToOne(inversedBy: 'allReponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserSondageResult $userSondageResult = null;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        $this->reponses->removeElement($reponse);

        return $this;
    }

    public function getUserSondageResult(): ?UserSondageResult
    {
        return $this->userSondageResult;
    }

    public function setUserSondageResult(?UserSondageResult $userSondageResult): self
    {
        $this->userSondageResult = $userSondageResult;

        return $this;
    }
}
