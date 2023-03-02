<?php

namespace App\Event;

use App\Entity\Data\ActivityTime;
use App\Entity\Data\SleepTime;
use App\Repository\Data\WeightRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ActivityTime::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ActivityTime::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: ActivityTime::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: ActivityTime::class)]
class ActivityTimeEvent
{
    public function __construct(private WeightRepository $weightRepo) { }

    public function prePersist(ActivityTime $activityTime, LifecycleEventArgs $event): void
    {
        $this->updateTime($activityTime);
    }

    public function preUpdate(ActivityTime $activityTime, LifecycleEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();
        $changes = array_keys($uow->getEntityChangeSet($activityTime));

        if (in_array('startAt', $changes) || in_array('endAt', $changes)) {
            $this->updateTime($activityTime);
        }
    }

    private function updateTime(ActivityTime $activityTime)
    {
        $activityTime->updateTime();
    }

    public function postUpdate(ActivityTime $activityTime, LifecycleEventArgs $event)
    {
        $dailyReport = $activityTime->getDailyReport();
        $event->getObjectManager()->refresh($dailyReport);
        $dailyReport->updateDailyActivityTime($this->weightRepo->findLastWeightByClient($dailyReport->getClient()));
    }

    public function postPersist(ActivityTime $activityTime, LifecycleEventArgs $event)
    {
        $dailyReport = $activityTime->getDailyReport();
        $event->getObjectManager()->refresh($dailyReport);
        $dailyReport->updateDailyActivityTime($this->weightRepo->findLastWeightByClient($dailyReport->getClient()));
    }
}