<?php

namespace App\Fixtures;

use App\Entity\Food;
use App\Entity\User\Client;
use App\Repository\User\ClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class FoodFixtures extends Fixture
{
    public function __construct()
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $client = $manager->getRepository(Client::class)->findAll();

        dd($client);

            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Dessert (aliment moyen)");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(36,6);
            $food->setProteins(4,23);
            $food->setLipids(12,2);
            $manager->persist($food);
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Taboulé ou Salade de couscous, préemballé");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(6,6);
            $food->setProteins(14,23);
            $food->setLipids(2,2);
            $manager->persist($food);
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Salade de thon et légumes, appertisée");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(3,6);
            $food->setProteins(41,23);
            $food->setLipids(1,2);
            $manager->persist($food);
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Champignons à la grecque, appertisés");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(26,6);
            $food->setProteins(4,23);
            $food->setLipids(2,2);
            $manager->persist($food);
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Salade de pommes de terre, fait maison");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(2,26);
            $food->setProteins(24,3);
            $food->setLipids(1,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Couscous au mouton");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(42,26);
            $food->setProteins(4,3);
            $food->setLipids(21,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Couscous à la viande ou au poulet, préemballé, allégé");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(22,26);
            $food->setProteins(12,3);
            $food->setLipids(11,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Dessert lacté infantile nature sucré ou aux fruits");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(0,26);
            $food->setProteins(3,3);
            $food->setLipids(3,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Dessert lacté infantile au riz ou à la semoule");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(12,26);
            $food->setProteins(3,3);
            $food->setLipids(3,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Base de pizza à la crème");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(52,26);
            $food->setProteins(4,3);
            $food->setLipids(1,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Son de riz");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(12,26);
            $food->setProteins(55,3);
            $food->setLipids(7,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Boisson diététique pour le sport");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(23,26);
            $food->setProteins(14,3);
            $food->setLipids(18,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Dinde, escalope, crue");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(0,26);
            $food->setProteins(24,3);
            $food->setLipids(1,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Poulet, filet, sans peau, cru, bio");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(2,26);
            $food->setProteins(0,3);
            $food->setLipids(1,1);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Cacahuète, grillée, sans sel ajouté");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(12,26);
            $food->setProteins(34,3);
            $food->setLipids(19,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Noisette grillée, salée");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(22,26);
            $food->setProteins(4,3);
            $food->setLipids(11,12);
            $manager->persist($food);
            
            
            $food = new Food();
            $food->setClient($client[array_rand($client)]);
            $food->setLabel("Noix de macadamia, grillée, salée");
            $food->setCalories($faker->randomFloat(2,20,300));
            $food->setCarbohydrates(9,26);
            $food->setProteins(7,3);
            $food->setLipids(1,12);
            $manager->persist($food);
            
            
            
            
            $manager->flush();

    }

    public function getDependencies()
    {
        return FoodFixtures::class ;
    }
}
