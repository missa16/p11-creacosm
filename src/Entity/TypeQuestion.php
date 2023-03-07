<?php

namespace App\Entity;

use App\Repository\TypeQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeQuestionRepository::class)]
class TypeQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intituleType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntituleType(): ?string
    {
        return $this->intituleType;
    }

    public function setIntituleType(string $intituleType): self
    {
        $this->intituleType = $intituleType;

        return $this;
    }


}
