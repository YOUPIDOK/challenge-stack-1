<?php

namespace App\Entity\Data;

use App\Entity\DailyReport;
use App\Entity\Food;
use App\Entity\User\Client;
use App\Enum\Nutrition\MealTypeEnum;
use App\Enum\Objective\ObjectiveTypeEnum;
use App\Repository\Data\NutritionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: NutritionRepository::class)]
#[ORM\Table(name: 'data__nutritions')]
class Nutrition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'nutritions')]
    #[NotNull]
    private ?Food $food = null;

    #[ORM\Column(length: 50)]
    #[NotNull]
    private ?string $mealType = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $foodWeight = null;

    #[ORM\ManyToOne(inversedBy: 'nutritions')]
    private ?DailyReport $dailyReport = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFood(): ?Food
    {
        return $this->food;
    }

    public function setFood(?Food $food): self
    {
        $this->food = $food;

        return $this;
    }

    public function getMealType(): ?string
    {
        return $this->mealType;
    }

    public function setMealType(?string $mealType): self
    {
        $this->mealType = $mealType;

        return $this;
    }

    public function getMealTypeValue(): ?string
    {
        if ($this->mealType !== null) {
            return MealTypeEnum::getType($this->mealType);
        }

        return null;
    }

    public function isBreakFast(): bool
    {
        return MealTypeEnum::BREAKFAST === $this->mealType;
    }


    public function getFoodWeight(): ?float
    {
        return $this->foodWeight;
    }

    public function setFoodWeight(?float $foodWeight): self
    {
        $this->foodWeight = $foodWeight;

        return $this;
    }

    public function getDailyReport(): ?DailyReport
    {
        return $this->dailyReport;
    }

    public function setDailyReport(?DailyReport $dailyReport): self
    {
        $this->dailyReport = $dailyReport;

        return $this;
    }
}
