<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Test = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): ?string
    {
        return $this->Test;
    }

    public function setTest(string $Test): self
    {
        $this->Test = $Test;

        return $this;
    }
}
