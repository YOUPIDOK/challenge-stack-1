<?php

namespace App\Entity;

use App\Entity\Data\Nutritions;
use App\Entity\User\Client;
use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
#[UniqueEntity(fields: ['label', 'client'])]
#[ORM\Table(name: 'foods')]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'food')]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    #[NotNull]
    private ?string $label = null;

    #[ORM\Column]
    #[NotNull]
    #[Range(min: 0)]
    private ?int $calories = null;

    #[ORM\Column]
    #[NotNull]
    #[Range(min: 0)]
    private ?int $carbohydrates = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?int $lipids = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?int $proteins = null;

    #[ORM\OneToMany(mappedBy: 'food', targetEntity: Nutritions::class, cascade: ['remove'])]
    private Collection $nutritions;

    public function __construct()
    {
        $this->nutritions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return '' . $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getCarbohydrates(): ?int
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?int $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getLipids(): ?int
    {
        return $this->lipids;
    }

    public function setLipids(?int $lipids): self
    {
        $this->lipids = $lipids;

        return $this;
    }

    public function getProteins(): ?int
    {
        return $this->proteins;
    }

    public function setProteins(?int $proteins): self
    {
        $this->proteins = $proteins;

        return $this;
    }

    /**
     * @return Collection<int, Nutritions>
     */
    public function getNutritions(): Collection
    {
        return $this->nutritions;
    }

    public function addNutrition(Nutritions $nutrition): self
    {
        if (!$this->nutritions->contains($nutrition)) {
            $this->nutritions->add($nutrition);
            $nutrition->setFood($this);
        }

        return $this;
    }

    public function removeNutrition(Nutritions $nutrition): self
    {
        if ($this->nutritions->removeElement($nutrition)) {
            // set the owning side to null (unless already changed)
            if ($nutrition->getFood() === $this) {
                $nutrition->setFood(null);
            }
        }

        return $this;
    }
}
