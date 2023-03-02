<?php

namespace App\Entity\User;

use App\Entity\Activity;
use App\Entity\DailyReport;
use App\Entity\Data\ActivityTime;
use App\Entity\Data\Nutrition;
use App\Entity\Data\SleepTime;
use App\Entity\Data\Weight;
use App\Entity\Food;
use App\Entity\Objective\Objective;
use App\Repository\User\ClientRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'user__clients')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotNull]
    private ?DateTime $birthdate = null;

    #[ORM\Column]
    #[NotNull]
    #[Range(min: 60, max: 260)]
    private ?int $height = null;

    #[ORM\Column]
    #[Timestampable(on: 'create')]
    private ?DateTime $registeredAt = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Activity::class, cascade: ['remove'])]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Objective::class, cascade: ['remove'])]
    private Collection $objectives;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: DailyReport::class, cascade: ['remove'])]
    #[ORM\OrderBy(['date' => 'DESC'])]
    private Collection $dailyReports;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Food::class, cascade: ['remove'])]
    #[ORM\OrderBy(['label' => 'ASC'])]
    private Collection $foods;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->objectives = new ArrayCollection();
        $this->dailyReports = new ArrayCollection();
        $this->foods = new ArrayCollection();
    }

    public function __toString(): string
    {
        return '' . $this->getUser();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getRegisteredAt(): ?DateTime
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(?DateTime $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getClient() !== $this) {
            $user->setClient($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setClient($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getClient() === $this) {
                $activity->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Objective>
     */
    public function getObjectives(): Collection
    {
        return $this->objectives;
    }

    public function addObjective(Objective $objective): self
    {
        if (!$this->objectives->contains($objective)) {
            $this->objectives->add($objective);
            $objective->setClient($this);
        }

        return $this;
    }


    public function removeObjective(Objective $objective): self
    {
        if ($this->objectives->removeElement($objective)) {
            // set the owning side to null (unless already changed)
            if ($objective->getClient() === $this) {
                $objective->setClient(null);
            }
        }
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
            $dailyReport->setClient($this);
        }

        return $this;
    }

    public function removeDailyReport(DailyReport $dailyReport): self
    {
        if ($this->dailyReports->removeElement($dailyReport)) {
            // set the owning side to null (unless already changed)
            if ($dailyReport->getClient() === $this) {
                $dailyReport->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return DailyReport|null
     */
    public function getCurrentDailyReport(): ?DailyReport {
        $dailyReport = $this->dailyReports->first();
        if ($dailyReport === false) {
            return null;
        }
        $today = new DateTime();
        if ( date_format($dailyReport->getDate(), 'Y-m-d') === date_format($today, 'Y-m-d')) {
            return $dailyReport;
        }
        return null;
    }

    /**
     * @return Collection<int, Food>
     */
    public function getFoods(): Collection
    {
        return $this->foods;
    }

    public function addFood(Food $food): self
    {
        if (!$this->foods->contains($food)) {
            $this->foods->add($food);
            $food->setClient($this);
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        if ($this->foods->removeElement($food)) {
            // set the owning side to null (unless already changed)
            if ($food->getClient() === $this) {
                $food->setClient(null);
            }
        }
        return $this;
    }
}
