<?php

namespace App\Entity\Data;

use App\Entity\Activity;
use App\Entity\DailyReport;
use App\Entity\User\Client;
use App\Repository\Data\ActivityTimesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ActivityTimesRepository::class)]
#[ORM\Table(name: 'data__activity_times')]
class ActivityTimes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activityTimes')]
    #[NotNull]
    private ?Client $client = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $startAt = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'activityTimes')]
    #[NotNull]
    private ?Activity $activity = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 0)]
    private ?float $distance = null;

    #[ORM\OneToMany(mappedBy: 'ActivityTimes', targetEntity: DailyReport::class)]
    private Collection $dailyReports;

    public function __construct()
    {
        $this->dailyReports = new ArrayCollection();
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

    /**
     * @return int
     */
    public function getActivityDuration(): int
    {
        $interval = date_diff($this->startAt, $this->endAt);
        return ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
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

    /**
     * @return Collection<int, DailyReport>
     */
    public function getDailyReports(): Collection
    {
        return $this->dailyReports;
    }

    public function addDailyReport(DailyReport $dailyReport): self
    {
        if (!$this->dailyReports->contains($dailyReport)) {
            $this->dailyReports->add($dailyReport);
            $dailyReport->setActivityTimes($this);
        }

        return $this;
    }

    public function removeDailyReport(DailyReport $dailyReport): self
    {
        if ($this->dailyReports->removeElement($dailyReport)) {
            // set the owning side to null (unless already changed)
            if ($dailyReport->getActivityTimes() === $this) {
                $dailyReport->setActivityTimes(null);
            }
        }

        return $this;
    }
}
