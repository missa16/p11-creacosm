<?php

namespace App\Entity;

use App\Repository\StatsQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsQuestionRepository::class)]
class StatsQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomStat = null;

    #[ORM\Column(length: 255)]
    private ?string $dataJson = null;

    #[ORM\ManyToOne(inversedBy: 'statsQuestions')]
    private ?Question $Question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStat(): ?string
    {
        return $this->nomStat;
    }

    public function setNomStat(string $nomStat): self
    {
        $this->nomStat = $nomStat;

        return $this;
    }

    public function getDataJson(): ?string
    {
        return $this->dataJson;
    }

    public function setDataJson(string $dataJson): self
    {
        $this->dataJson = $dataJson;

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
}
