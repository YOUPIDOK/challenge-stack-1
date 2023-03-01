<?php

namespace App\Fixtures;


use App\Entity\Objective\Objective ;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class ObjectiveFixtures extends Fixture
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
            $objective = new Objective();
            $objective->setClientId($client[array_rand($client)]);
            $objective->setObjectiveValue(rand(0, 10000));
            $objective->setType($this->faker->randomElement([ "Moyenne d'heure de sommeil par jour","Moyenne calorique dépensée par jour","Moyenne calorique assimilée par jour","Heure d'actvité sur la période", "Moyenne kilomètre parcourus","Moyenne déficite calorique par jour","Poid à la fin de la période"]));
            $objective->setActive($this->faker->boolean());
            $objective->setStartAt(new \DateTime());
            $objective->setEndAt($this->faker->dateTimeBetween('now', 'now + 98 days'));

            $manager->persist($objective);
        }
        
        $manager->flush();
    }

    public function getDependencies()
    {
        return ObjectiveFixtures::class ;
    }
}
