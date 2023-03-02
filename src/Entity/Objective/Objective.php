<?php

namespace App\Entity\Objective;

use App\Entity\User\Client;
use App\Enum\Objective\ObjectiveTypeEnum;
use App\Enum\User\GenderEnum;
use App\Repository\Objective\ObjectiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ObjectiveRepository::class)]
#[ORM\Table(name: 'objective__objectives')]
class Objective
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endAt = null;

    #[ORM\Column(length: 255)]
    #[NotNull]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'objectives')]
    #[NotNull]
    private ?Client $client = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $objectiveValue = null;

    #[ORM\Column()]
    #[NotNull]
    private ?bool $active = null;

    #[ORM\Column(length: 255)]
    #[NotNull]
    private ?string $type = null;

    public function __construct()
    {
        $this->active = true;
    }

    /**
     * @return string|null
     */
    public function getObjectiveTypeValue(): ?string
    {
        if ($this->type !== null) {
            return ObjectiveTypeEnum::getType($this->type);
        }

        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTime $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
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
}
