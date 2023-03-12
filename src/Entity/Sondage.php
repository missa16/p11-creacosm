<?php

namespace App\Entity;

use App\Repository\SondageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SondageRepository::class)]
#[Vich\Uploadable]
class Sondage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $intitule = null;

    #[ORM\Column(length: 255)]
    private ?string $etatSondage = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageCouverture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateLancement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpdate = null;

    #[ORM\OneToMany(mappedBy: 'sondage', targetEntity: Question::class,cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $Questions;

    #[ORM\OneToMany(mappedBy: 'sondage', targetEntity: UserSondageResult::class, orphanRemoval: true)]
    private Collection $lesSondes;



    public function __construct()
    {
        $this->Questions = new ArrayCollection();
        $this->lesSondes = new ArrayCollection();

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

    public function getEtatSondage(): ?string
    {
        return $this->etatSondage;
    }

    public function setEtatSondage(string $etatSondage): self
    {
        $this->etatSondage = $etatSondage;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageCouverture(): ?string
    {
        return $this->imageCouverture;
    }

    public function setImageCouverture(string $imageCouverture): self
    {
        $this->imageCouverture = $imageCouverture;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateLancement(): ?\DateTimeInterface
    {
        return $this->dateLancement;
    }

    public function setDateLancement(\DateTimeInterface $dateLancement): self
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate(\DateTimeInterface $dateUpdate): self
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->Questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->Questions->contains($question)) {
            $this->Questions->add($question);
            $question->setSondage($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->Questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSondage() === $this) {
                $question->setSondage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSondageResult>
     */
    public function getLesSondes(): Collection
    {
        return $this->lesSondes;
    }

    public function addLesSonde(UserSondageResult $lesSonde): self
    {
        if (!$this->lesSondes->contains($lesSonde)) {
            $this->lesSondes->add($lesSonde);
            $lesSonde->setSondage($this);
        }

        return $this;
    }

    public function removeLesSonde(UserSondageResult $lesSonde): self
    {
        if ($this->lesSondes->removeElement($lesSonde)) {
            // set the owning side to null (unless already changed)
            if ($lesSonde->getSondage() === $this) {
                $lesSonde->setSondage(null);
            }
        }

        return $this;
    }








}
