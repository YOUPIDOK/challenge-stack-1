<?php

namespace App\Entity\Objective;

use App\Enum\Objective\ObjectiveTypeEnum;
use App\Repository\Objective\SelectObjectivesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: SelectObjectivesRepository::class)]
class SelectObjectives
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[NotNull]
    private ?string $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    #[NotNull]
    private ?string $objectiveValue = null;

    #[ORM\ManyToOne(inversedBy: 'selectObjectives')]
    #[NotNull]
    private ?Objectives $Objectives = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeValue()
    {
        if ($this->type !== null) {
            return ObjectiveTypeEnum::getType($this->type);
        }

        return null;
    }

    public function getObjectiveValue(): ?string
    {
        return $this->objectiveValue;
    }

    public function setObjectiveValue(?string $objectiveValue): self
    {
        $this->objectiveValue = $objectiveValue;

        return $this;
    }

    public function getObjectives(): ?Objectives
    {
        return $this->Objectives;
    }

    public function setObjectives(?Objectives $Objectives): self
    {
        $this->Objectives = $Objectives;

        return $this;
    }
}
