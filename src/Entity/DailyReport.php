<?php

namespace App\Entity;

use App\Entity\Data\ActivityTime;
use App\Entity\Data\Nutrition;
use App\Entity\Data\SleepTime;
use App\Entity\Data\Weight;
use App\Entity\User\Client;
use App\Repository\DailyReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'dailyReport', targetEntity: SleepTime::class, cascade: ['remove'])]
    private Collection $sleepTimes;

    #[ORM\OneToMany(mappedBy: 'dailyReport', targetEntity: Nutrition::class, cascade: ['remove'])]
    private Collection $nutritions;

    #[ORM\OneToMany(mappedBy: 'dailyReport', targetEntity: ActivityTime::class, cascade: ['remove'])]
    private Collection $activityTimes;

    #[ORM\OneToMany(mappedBy: 'dailyReport', targetEntity: Weight::class, cascade: ['remove'])]
    private Collection $weights;

    public function __construct()
    {
        $this->sleepTimes = new ArrayCollection();
        $this->nutritions = new ArrayCollection();
        $this->activityTimes = new ArrayCollection();
        $this->weights = new ArrayCollection();
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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, SleepTime>
     */
    public function getSleepTimes(): Collection
    {
        return $this->sleepTimes;
    }

    public function addSleepTime(SleepTime $sleepTime): self
    {
        if (!$this->sleepTimes->contains($sleepTime)) {
            $this->sleepTimes->add($sleepTime);
            $sleepTime->setDailyReport($this);
        }

        return $this;
    }

    public function removeSleepTime(SleepTime $sleepTime): self
    {
        if ($this->sleepTimes->removeElement($sleepTime)) {
            // set the owning side to null (unless already changed)
            if ($sleepTime->getDailyReport() === $this) {
                $sleepTime->setDailyReport(null);
            }
        }

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
            $nutrition->setDailyReport($this);
        }

        return $this;
    }

    public function removeNutrition(Nutrition $nutrition): self
    {
        if ($this->nutritions->removeElement($nutrition)) {
            // set the owning side to null (unless already changed)
            if ($nutrition->getDailyReport() === $this) {
                $nutrition->setDailyReport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityTime>
     */
    public function getActivityTimes(): Collection
    {
        return $this->activityTimes;
    }

    public function addActivityTime(ActivityTime $activityTime): self
    {
        if (!$this->activityTimes->contains($activityTime)) {
            $this->activityTimes->add($activityTime);
            $activityTime->setDailyReport($this);
        }

        return $this;
    }

    public function removeActivityTime(ActivityTime $activityTime): self
    {
        if ($this->activityTimes->removeElement($activityTime)) {
            // set the owning side to null (unless already changed)
            if ($activityTime->getDailyReport() === $this) {
                $activityTime->setDailyReport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Weight>
     */
    public function getWeights(): Collection
    {
        return $this->weights;
    }

    public function addWeight(Weight $weight): self
    {
        if (!$this->weights->contains($weight)) {
            $this->weights->add($weight);
            $weight->setDailyReport($this);
        }

        return $this;
    }

    public function removeWeight(Weight $weight): self
    {
        if ($this->weights->removeElement($weight)) {
            // set the owning side to null (unless already changed)
            if ($weight->getDailyReport() === $this) {
                $weight->setDailyReport(null);
            }
        }

        return $this;
    }
}
