<?php

namespace App\Fixtures;

use App\Entity\Activity;
use App\Entity\Data\ActivityTime;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class ActivityFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $client = $manager->getRepository(Client::class)->findAll();   

        
            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("marche à pieds");
            $activity->setHeartRate(105);
            $activity->setIsDistance(true);
            $manager->persist($activity);

            $ActivityTime = new ActivityTime();
            $ActivityTime->setClient($client[array_rand($client)]);
            $ActivityTime->setStartAt($this->faker->dateTimeBetween('-7 days', 'now')->format('H:i'));
            $ActivityTime->setEndAt($this->faker->dateTimeBetween('now', '+7 days')->format('H:i'));
            $ActivityTime->setActivity($activity);
            $ActivityTime->setDistance(rand(10, 10000));
            $manager->persist($ActivityTime);
        
            dd("walid");

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("marche  nordique");
            $activity->setHeartRate(120);
            $activity->setIsDistance(true);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("courses");
            $activity->setHeartRate(160);
            $activity->setIsDistance(true);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Natation");
            $activity->setHeartRate(130);
            $activity->setIsDistance(true);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Aviron");
            $activity->setHeartRate(150);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Corde à sauter");
            $activity->setHeartRate(160);
            $activity->setIsDistance(true);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Vélo");
            $activity->setHeartRate(145);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Boxe");
            $activity->setHeartRate(140);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Tennis");
            $activity->setHeartRate(150);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Rugby");
            $activity->setHeartRate(150);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Basket");
            $activity->setHeartRate(150);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Football");
            $activity->setHeartRate(150);
            $activity->setIsDistance(false);
            $manager->persist($activity);

            $activity = new Activity();
            $activity->setClient($client[array_rand($client)]);
            $activity->setLabel("Crossfit");
            $activity->setHeartRate(165);
            $activity->setIsDistance(false);
            $manager->persist($activity);



        
        $manager->flush();
    }

    public function getDependencies()
    {
        return ActivityFixtures::class ;
    }
}
