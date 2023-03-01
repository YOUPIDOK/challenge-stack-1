<?php

namespace App\Fixtures;

use App\Entity\Data\Weight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class WeightFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $client = $manager->getRepository(Client::class)->findAll();   

        for ($i = 1; $i <= 20; $i++) {
            $weight = new Weight();
            $weight->setClient($client[array_rand($client)]);
            $weight->setWeight(rand(50, 180));
            $weight->setDailyReport();

            $manager->persist($weight);
        }
        
        $manager->flush();
    }

    public function getDependencies()
    {
        return WeightFixtures::class ;
    }
}
