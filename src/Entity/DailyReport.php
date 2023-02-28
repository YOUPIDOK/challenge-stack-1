<?php

namespace App\Entity;

use App\Entity\Data\ActivityTimes;
use App\Entity\Data\Nutritions;
use App\Entity\Data\SleepTime;
use App\Entity\Data\Weights;
use App\Entity\User\Client;
use App\Repository\DailyReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: DailyReportRepository::class)]
class DailyReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dailyReports')]
    #[NotNull]
    private ?Client $client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotNull]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'dailyReports')]
    private ?SleepTime $sleepTime = null;

    #[ORM\ManyToOne(inversedBy: 'dailyReports')]
    private ?Nutritions $nutritions = null;

    #[ORM\ManyToOne(inversedBy: 'dailyReports')]
    private ?ActivityTimes $activityTimes = null;

    #[ORM\ManyToOne(inversedBy: 'dailyReports')]
    private ?Weights $weights = null;

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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSleepTime(): ?SleepTime
    {
        return $this->sleepTime;
    }

    public function setSleepTime(?SleepTime $sleepTime): self
    {
        $this->sleepTime = $sleepTime;

        return $this;
    }

    public function getNutritions(): ?Nutritions
    {
        return $this->nutritions;
    }

    public function setNutritions(?Nutritions $nutritions): self
    {
        $this->nutritions = $nutritions;

        return $this;
    }

    public function getActivityTimes(): ?ActivityTimes
    {
        return $this->activityTimes;
    }

    public function setActivityTimes(?ActivityTimes $activityTimes): self
    {
        $this->activityTimes = $activityTimes;

        return $this;
    }

    public function getWeights(): ?Weights
    {
        return $this->weights;
    }

    public function setWeights(?Weights $weights): self
    {
        $this->weights = $weights;

        return $this;
    }
}
