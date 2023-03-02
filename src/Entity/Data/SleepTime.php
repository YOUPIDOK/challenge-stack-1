<?php

namespace App\Entity\Data;

use App\Entity\DailyReport;
use App\Entity\User\Client;
use App\Repository\Data\SleepTimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: SleepTimeRepository::class)]
#[ORM\Table(name: 'data__sleep_times')]
class SleepTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $asleepAt = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $awakeAt = null;

    #[ORM\ManyToOne(inversedBy: 'sleepTimes')]
    private ?DailyReport $dailyReport = null;

    #[ORM\Column]
    private ?int $time = 0;

    #[ORM\Column]
    private ?float $timeInHour = 0;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAsleepAt(): ?\DateTime
    {
        return $this->asleepAt;
    }

    public function setAsleepAt(?\DateTime $asleepAt): self
    {
        $this->asleepAt = $asleepAt;

        return $this;
    }

    public function getAwakeAt(): ?\DateTime
    {
        return $this->awakeAt;
    }

    public function setAwakeAt(?\DateTime $awakeAt): self
    {
        $this->awakeAt = $awakeAt;

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
        $this->time = ($this->awakeAt->getTimestamp() - $this->asleepAt->getTimestamp()) / 60;
        $this->timeInHour = round($this->time / 60.0, 2);

        return $this;
    }

    public function getTimeInHour(): ?float
    {
        return $this->timeInHour;
    }

    public function setTimeInHour(float $timeInHour): self
    {
        $this->timeInHour = $timeInHour;

        return $this;
    }
}
