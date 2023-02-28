<?php

namespace App\Entity\User;

use App\Entity\Activity;
use App\Entity\Data\ActivityTimes;
use App\Entity\Data\Nutritions;
use App\Entity\Data\SleepTime;
use App\Entity\Data\Weights;
use App\Entity\Food;
use App\Entity\Objective\Objectives;
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

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Food::class, cascade: ['remove'])]
    private Collection $food;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Activity::class, cascade: ['remove'])]
    private Collection $activities;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Objectives::class, cascade: ['remove'])]
    private Collection $objectives;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: SleepTime::class, cascade: ['remove'])]
    private Collection $sleepTimes;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Weights::class, cascade: ['remove'])]
    private Collection $weights;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Nutritions::class, cascade: ['remove'])]
    private Collection $nutritions;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: ActivityTimes::class, cascade: ['remove'])]
    private Collection $activityTimes;

    public function __construct()
    {
        $this->food = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->objectives = new ArrayCollection();
        $this->sleepTimes = new ArrayCollection();
        $this->weights = new ArrayCollection();
        $this->nutritions = new ArrayCollection();
        $this->activityTimes = new ArrayCollection();
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
     * @return Collection<int, Food>
     */
    public function getFood(): Collection
    {
        return $this->food;
    }

    public function addFood(Food $food): self
    {
        if (!$this->food->contains($food)) {
            $this->food->add($food);
            $food->setClient($this);
        }

        return $this;
    }

    public function removeFood(Food $food): self
    {
        if ($this->food->removeElement($food)) {
            // set the owning side to null (unless already changed)
            if ($food->getClient() === $this) {
                $food->setClient(null);
            }
        }

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
     * @return Collection<int, Objectives>
     */
    public function getObjectives(): Collection
    {
        return $this->objectives;
    }

    public function addObjective(Objectives $objective): self
    {
        if (!$this->objectives->contains($objective)) {
            $this->objectives->add($objective);
            $objective->setClientId($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSleepTimes(): Collection
    {
        return $this->sleepTimes;
    }

    public function addSleepTime(SleepTime $sleepTime): self
    {
        if (!$this->sleepTimes->contains($sleepTime)) {
            $this->sleepTimes->add($sleepTime);
            $sleepTime->setClient($this);
        }

        return $this;
    }


    public function removeObjective(Objectives $objective): self
    {
        if ($this->objectives->removeElement($objective)) {
            // set the owning side to null (unless already changed)
            if ($objective->getClientId() === $this) {
                $objective->setClientId(null);
            }
        }
        return $this;
    }

    public function removeSleepTime(SleepTime $sleepTime): self
    {
        if ($this->sleepTimes->removeElement($sleepTime)) {
            // set the owning side to null (unless already changed)
            if ($sleepTime->getClient() === $this) {
                $sleepTime->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Weights>
     */
    public function getWeights(): Collection
    {
        return $this->weights;
    }

    public function addWeight(Weights $weight): self
    {
        if (!$this->weights->contains($weight)) {
            $this->weights->add($weight);
            $weight->setClient($this);
        }

        return $this;
    }

    public function removeWeight(Weights $weight): self
    {
        if ($this->weights->removeElement($weight)) {
            // set the owning side to null (unless already changed)
            if ($weight->getClient() === $this) {
                $weight->setClient(null);
            }
        }

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
            $nutrition->setClient($this);
        }

        return $this;
    }

    public function removeNutrition(Nutritions $nutrition): self
    {
        if ($this->nutritions->removeElement($nutrition)) {
            // set the owning side to null (unless already changed)
            if ($nutrition->getClient() === $this) {
                $nutrition->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivityTimes>
     */
    public function getActivityTimes(): Collection
    {
        return $this->activityTimes;
    }

    public function addActivityTime(ActivityTimes $activityTime): self
    {
        if (!$this->activityTimes->contains($activityTime)) {
            $this->activityTimes->add($activityTime);
            $activityTime->setClient($this);
        }

        return $this;
    }

    public function removeActivityTime(ActivityTimes $activityTime): self
    {
        if ($this->activityTimes->removeElement($activityTime)) {
            // set the owning side to null (unless already changed)
            if ($activityTime->getClient() === $this) {
                $activityTime->setClient(null);
            }
        }

        return $this;
    }
}
