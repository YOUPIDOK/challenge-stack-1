<?php

namespace App\Entity\Objective;

use App\Entity\User\Client;
use App\Repository\Objective\ObjectivesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: ObjectivesRepository::class)]
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

    #[ORM\OneToMany(mappedBy: 'Objective', targetEntity: SelectObjectives::class, cascade: ['remove'])]
    #[Count(min: 1)]
    #[NotNull]
    private Collection $selectObjectives;

    public function __construct()
    {
        $this->selectObjectives = new ArrayCollection();
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

    public function setClientId(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, SelectObjectives>
     */
    public function getSelectObjectives(): Collection
    {
        return $this->selectObjectives;
    }

    public function addSelectObjective(SelectObjectives $selectObjective): self
    {
        if (!$this->selectObjectives->contains($selectObjective)) {
            $this->selectObjectives->add($selectObjective);
            $selectObjective->setObjectives($this);
        }

        return $this;
    }

    public function removeSelectObjective(SelectObjectives $selectObjective): self
    {
        if ($this->selectObjectives->removeElement($selectObjective)) {
            // set the owning side to null (unless already changed)
            if ($selectObjective->getObjectives() === $this) {
                $selectObjective->setObjectives(null);
            }
        }

        return $this;
    }
}
