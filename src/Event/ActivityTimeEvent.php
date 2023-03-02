<?php

namespace App\Event;

use App\Entity\Data\ActivityTime;
use App\Entity\Data\SleepTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ActivityTime::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ActivityTime::class)]
class ActivityTimeEvent
{
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
}