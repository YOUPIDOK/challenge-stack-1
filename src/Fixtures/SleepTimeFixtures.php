<?php

namespace App\Fixtures;

use App\Entity\Data\SleepTime;
use App\Repository\DailyReportRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class SleepTimeFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $client = $manager->getRepository(Client::class)->findAll();   
        $dailyReport = DailyReportRepository;   

        for ($i = 1; $i <= 20; $i++) {
            $sleepTime = new SleepTime();
            $sleepTime->setClient($client[array_rand($client)]);
            $sleepTime->setAsleepAt((clone $dailyReport->getDate())->modify('-1 day'));
            $sleepTime->setAwakeAt((clone$dailyReport->getDate())->modify('+23 hour')->modify('+59 minute'));

            $manager->persist($sleepTime);
        }
        
        $manager->flush();
    }

    public function getDependencies()
    {
        return SleepTimeFixtures::class ;
    }
}
