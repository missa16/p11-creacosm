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

    #[ORM\Column(length: 255)]
    private bool $isMultiple = false;

    #[ORM\Column(length: 255)]
    private bool $isExpanded = false;

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





    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }

    /**
     * @param bool $isMultiple
     */
    public function setIsMultiple(bool $isMultiple): void
    {
        $this->isMultiple = $isMultiple;
    }

    /**
     * @return bool
     */
    public function isExpanded(): bool
    {
        return $this->isExpanded;
    }

    /**
     * @param bool $isExpanded
     */
    public function setIsExpanded(bool $isExpanded): void
    {
        $this->isExpanded = $isExpanded;
    }


}
