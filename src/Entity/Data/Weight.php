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
class Weight
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

    #[ORM\ManyToOne(inversedBy: 'weights')]
    private ?DailyReport $dailyReport = null;

    public function __construct()
    {
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
