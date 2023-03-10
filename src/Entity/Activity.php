<?php

namespace App\Entity;

use App\Entity\Data\ActivityTime;
use App\Entity\User\Client;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[ORM\Table(name: 'activities')]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    #[NotNull]
    private ?string $label = null;

    #[ORM\Column]
    #[NotNull]
    #[Range(min: 60)]
    private ?int $heartRate = null;

    #[ORM\Column]
    #[NotNull]
    private ?bool $isDistance = false;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityTime::class, cascade: ['remove'])]
    private Collection $activityTimes;

    public function __construct()
    {
        $this->activityTimes = new ArrayCollection();
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

    public function getHeartRate(): ?int
    {
        return $this->heartRate;
    }

    public function setHeartRate(?int $heartRate): self
    {
        $this->heartRate = $heartRate;

        return $this;
    }

    public function isIsDistance(): ?bool
    {
        return $this->isDistance;
    }

    public function setIsDistance(?bool $isDistance): self
    {
        $this->isDistance = $isDistance;

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
            $activityTime->setActivity($this);
        }

        return $this;
    }

    public function removeActivityTime(ActivityTime $activityTime): self
    {
        if ($this->activityTimes->removeElement($activityTime)) {
            // set the owning side to null (unless already changed)
            if ($activityTime->getActivity() === $this) {
                $activityTime->setActivity(null);
            }
        }

        return $this;
    }
}
