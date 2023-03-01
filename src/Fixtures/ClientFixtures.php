<?php

namespace App\Fixtures;

use App\Entity\User\Client;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;


class ClientFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {


        
        // $client = $manager->getRepository(Client::class)->findAll();
        $User = new User();
        $User->setEmail($this->faker->email());
        $User->setRoles(["ROLE_SUPER_ADMIN"]);
        $User->setPassword('$2y$13$F7jb35RAj8hkGFXA0ToHgudxSN80suhJKwJnQiIfTxxcJHyS3XByu');
        $User->setFirstname($this->faker->firstname);
        $User->setLastname($this->faker->lastName);
        $User->setGender($this->faker->randomElement([ 'MAN','WOMAN','NON_BINARY']));
        $User->setUpdatePasswordAt(new \DateTime());
        $User->setEnabled(true);
        $manager->persist($User);
        

        for ($i = 1; $i <= 20; $i++) {
            $client = new Client();
            $client->setBirthdate($this->faker->dateTimeBetween('now -80 years', 'now -10 years') );
            $client->setHeight($this->faker->randomFloat(2,50,220));
            $client->setRegisteredAt(new \DateTime());
            $manager->persist($client);

            $User = new User();
            $User->setEmail($this->faker->email());
            $User->setPassword('$2y$13$F7jb35RAj8hkGFXA0ToHgudxSN80suhJKwJnQiIfTxxcJHyS3XByu');
            $User->setFirstname($this->faker->firstname);
            $User->setLastname($this->faker->lastName);
            $User->setGender($this->faker->randomElement(['MAN','WOMAN','NON_BINARY']));
            $User->setUpdatePasswordAt(new \DateTime());
            $User->setEnabled(true);
            $User->setClient($client->getId());
            $manager->persist($User);

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return ClientFixtures::class ;
    }
}
