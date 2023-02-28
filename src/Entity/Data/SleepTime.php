<?php

namespace App\Entity\Data;

use App\Entity\User\Client;
use App\Repository\Data\SleepTimeRepository;
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

    #[ORM\ManyToOne(inversedBy: 'sleepTimes')]
    #[NotNull]
    private ?Client $client = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $asleepAt = null;

    #[ORM\Column()]
    #[NotNull]
    private ?\DateTime $awakeAt = null;

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
}
