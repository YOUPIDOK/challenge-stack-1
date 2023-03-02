<?php

namespace App\Entity\Data;

use App\Entity\Activity;
use App\Entity\DailyReport;
use App\Entity\User\Client;
use App\Repository\Data\ActivityTimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ActivityTimeRepository::class)]
#[ORM\Table(name: 'data__activity_times')]
class ActivityTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $startAt = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'activityTimes')]
    #[NotNull]
    private ?Activity $activity = null;

    #[ORM\Column(nullable: true)]
    #[Range(min: 0)]
    private ?float $distance = null;

    #[ORM\ManyToOne(inversedBy: 'activityTimes')]
    private ?DailyReport $dailyReport = null;

    #[ORM\Column]
    #[Range(min: 0)]
    private ?int $time = null;

    public function __construct()
    {
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

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): self
    {
        $this->distance = $distance;

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

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function updateTime(): self
    {
        $this->time = ($this->endAt->getTimestamp() - $this->startAt->getTimestamp()) / 60;

        return $this;
    }
}
