<?php

namespace App\Entity\Objective;

use App\Enum\Objective\ObjectiveTypeEnum;
use App\Repository\Objective\SelectObjectivesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: SelectObjectivesRepository::class)]
#[ORM\Table(name: 'objective__select_objective')]
class SelectObjectives
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[NotNull]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'selectObjectives')]
    #[NotNull]
    private ?Objective $Objectives = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $objectiveValue = null;

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

    public function getTypeValue(): ?string
    {
        if ($this->type !== null) {
            return ObjectiveTypeEnum::getType($this->type);
        }

        return null;
    }

    public function getObjectives(): ?Objective
    {
        return $this->Objectives;
    }

    public function setObjectives(?Objective $Objectives): self
    {
        $this->Objectives = $Objectives;

        return $this;
    }

    public function getObjectiveValue(): ?float
    {
        return $this->objectiveValue;
    }

    public function setObjectiveValue(?float $objectiveValue): self
    {
        $this->objectiveValue = $objectiveValue;

        return $this;
    }
}
