<?php

namespace App\Event;

use App\Entity\Data\Nutrition;
use App\Entity\Data\SleepTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Nutrition::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Nutrition::class)]
class NutritionEvent
{
    public function prePersist(Nutrition $nutrition, LifecycleEventArgs $event): void
    {
        $nutrition->updateDailyNutrition();
    }

    public function preUpdate(Nutrition $nutrition, LifecycleEventArgs $event): void
    {
        $nutrition->updateDailyNutrition();
    }
}