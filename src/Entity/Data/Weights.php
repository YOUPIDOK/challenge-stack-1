<?php

namespace App\Entity\Data;

use App\Entity\DailyReport;
use App\Entity\User\Client;
use App\Repository\Data\WeightsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: WeightsRepository::class)]
#[ORM\Table(name: 'data__weights')]
class Weights
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'weights')]
    #[NotNull]
    private ?Client $client = null;

    #[ORM\Column()]
    #[NotNull]
    #[Range(min: 20, max: 400)]
    private ?float $weight = null;

    #[ORM\OneToMany(mappedBy: 'weights', targetEntity: DailyReport::class)]
    private Collection $dailyReports;

    public function __construct()
    {
        $this->dailyReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

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
            $dailyReport->setWeights($this);
        }

        return $this;
    }

    public function removeDailyReport(DailyReport $dailyReport): self
    {
        if ($this->dailyReports->removeElement($dailyReport)) {
            // set the owning side to null (unless already changed)
            if ($dailyReport->getWeights() === $this) {
                $dailyReport->setWeights(null);
            }
        }

        return $this;
    }
}
