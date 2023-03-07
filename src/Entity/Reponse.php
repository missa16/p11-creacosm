<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
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

}
