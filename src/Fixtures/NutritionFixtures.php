<?php

namespace App\Fixtures;

use App\Entity\Data\Nutrition;
use App\Entity\Food;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class NutritionFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {



        $client = $manager->getRepository(Client::class)->findAll();   
        $food = $manager->getRepository(Food::class)->findAll();   

        for ($i = 1; $i <= 20; $i++) {
            $nutrition = new Nutrition();
            $nutrition->setClient($client[array_rand($client)]);
            $nutrition->setFood($food[array_rand($food)]);
            $nutrition->setMealType($this->faker->randomElement([ 'BREAKFAST','LUNCH','DINNER','SNACK']));
            $nutrition->setFoodWeight(rand(0, 10000));

            $manager->persist($nutrition);
        }
        
        $manager->flush();
    }

    public function getDependencies()
    {
        return NutritionFixtures::class ;
    }
}
