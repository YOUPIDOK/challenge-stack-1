<?php

namespace App\Entity;

use App\Entity\Data\Nutrition;
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

    #[ORM\Column(length: 255)]
    #[NotNull]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'food', targetEntity: Nutrition::class, cascade: ['remove'])]
    private Collection $nutritions;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $calories = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $carbohydrates = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $lipids = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $proteins = null;

    #[ORM\ManyToOne(inversedBy: 'foods')]
    private ?Client $client = null;

    public function __construct()
    {
        $this->nutritions = new ArrayCollection();
    }

    public function __toString(): string
    {
        $calories = $this->calories ? (' (' . $this->calories . 'kcal)') : '';

        return '' . $this->label . $calories;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Nutrition>
     */
    public function getNutritions(): Collection
    {
        return $this->nutritions;
    }

    public function addNutrition(Nutrition $nutrition): self
    {
        if (!$this->nutritions->contains($nutrition)) {
            $this->nutritions->add($nutrition);
            $nutrition->setFood($this);
        }

        return $this;
    }

    public function removeNutrition(Nutrition $nutrition): self
    {
        if ($this->nutritions->removeElement($nutrition)) {
            // set the owning side to null (unless already changed)
            if ($nutrition->getFood() === $this) {
                $nutrition->setFood(null);
            }
        }

        return $this;
    }

    public function getCalories(): ?float
    {
        return $this->calories;
    }

    public function setCalories(?float $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getCarbohydrates(): ?float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(?float $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getLipids(): ?float
    {
        return $this->lipids;
    }

    public function setLipids(?float $lipids): self
    {
        $this->lipids = $lipids;

        return $this;
    }

    public function getProteins(): ?float
    {
        return $this->proteins;
    }

    public function setProteins(?float $proteins): self
    {
        $this->proteins = $proteins;

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
}
