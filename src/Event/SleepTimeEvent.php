<?php

namespace App\Event;

use App\Entity\Data\SleepTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: SleepTime::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: SleepTime::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: SleepTime::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: SleepTime::class)]
class SleepTimeEvent
{
    public function prePersist(SleepTime $sleepTime, LifecycleEventArgs $event): void
    {
        $this->updateTime($sleepTime);
    }

    public function preUpdate(SleepTime $sleepTime, LifecycleEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();
        $changes = array_keys($uow->getEntityChangeSet($sleepTime));

        if (in_array('asleepAt', $changes) || in_array('awakeAt', $changes)) {
            $this->updateTime($sleepTime);
        }
    }

    private function updateTime(SleepTime $sleepTime)
    {
        $sleepTime->updateTime();
    }

    public function postUpdate(SleepTime $sleepTime, LifecycleEventArgs $event)
    {
       $dailyReport =  $sleepTime->getDailyReport();
       $event->getObjectManager()->refresh($dailyReport);
       $dailyReport->updateDailyReportSleepTime();
    }

    public function postPersist(SleepTime $sleepTime, LifecycleEventArgs $event)
    {
        $dailyReport =  $sleepTime->getDailyReport();
        $event->getObjectManager()->refresh($dailyReport);
        $dailyReport->updateDailyReportSleepTime();
    }
}