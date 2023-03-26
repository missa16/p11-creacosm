<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $intitule = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeQuestion $typeQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'Questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sondage $sondage = null;

    #[ORM\OneToMany(mappedBy: 'Question', targetEntity: Reponse::class,cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $Reponses;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: UserSondageReponse::class, orphanRemoval: true)]
    private Collection $userSondageReponses;

    #[ORM\OneToMany(mappedBy: 'Question', targetEntity: StatsQuestion::class)]
    private Collection $statsQuestions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageQuestion = null;




    public function __construct()
    {
        $this->Reponses = new ArrayCollection();
        $this->userSondageReponses = new ArrayCollection();
        $this->statsQuestion = new ArrayCollection();
        $this->statsQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getTypeQuestion(): ?TypeQuestion
    {
        return $this->typeQuestion;
    }

    public function setTypeQuestion(?TypeQuestion $typeQuestion): self
    {
        $this->typeQuestion = $typeQuestion;

        return $this;
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
     * @return Collection<int, Reponse>
     */
    public function getReponses(): Collection
    {
        return $this->Reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->Reponses->contains($reponse)) {
            $this->Reponses->add($reponse);
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->Reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
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
            $userSondageReponse->setQuestion($this);
        }

        return $this;
    }

    public function removeUserSondageReponse(UserSondageReponse $userSondageReponse): self
    {
        if ($this->userSondageReponses->removeElement($userSondageReponse)) {
            // set the owning side to null (unless already changed)
            if ($userSondageReponse->getQuestion() === $this) {
                $userSondageReponse->setQuestion(null);
            }
        }

        return $this;
    }




    /**
     * @return Collection<int, StatsQuestion>
     */
    public function getStatsQuestions(): Collection
    {
        return $this->statsQuestions;
    }

    public function addStatsQuestion(StatsQuestion $statsQuestion): self
    {
        if (!$this->statsQuestions->contains($statsQuestion)) {
            $this->statsQuestions->add($statsQuestion);
            $statsQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeStatsQuestion(StatsQuestion $statsQuestion): self
    {
        if ($this->statsQuestions->removeElement($statsQuestion)) {
            // set the owning side to null (unless already changed)
            if ($statsQuestion->getQuestion() === $this) {
                $statsQuestion->setQuestion(null);
            }
        }

        return $this;
    }

    public function getImageQuestion(): ?string
    {
        return $this->imageQuestion;
    }

    public function setImageQuestion(?string $imageQuestion): self
    {
        $this->imageQuestion = $imageQuestion;

        return $this;
    }


}
