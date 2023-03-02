<?php

namespace App\Event;

use App\Entity\Data\Nutrition;
use App\Entity\Data\SleepTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Nutrition::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Nutrition::class)]
class NutritionEvent
{
    public function postPersist(Nutrition $nutrition, LifecycleEventArgs $event): void
    {
        $dailyReport =  $nutrition->getDailyReport();
        $event->getObjectManager()->refresh($dailyReport);
        $dailyReport->updateDailyNutrition();
    }

    public function postUpdate(Nutrition $nutrition, LifecycleEventArgs $event): void
    {
        $dailyReport =  $nutrition->getDailyReport();
        $event->getObjectManager()->refresh($dailyReport);
        $dailyReport->updateDailyNutrition();
    }
}