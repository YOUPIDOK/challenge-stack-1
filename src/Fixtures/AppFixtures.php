<?php

namespace App\Fixtures;

use App\Entity\Activity;
use App\Entity\DailyReport;
use App\Entity\Data\ActivityTime;
use App\Entity\Data\Nutrition;
use App\Entity\Data\SleepTime;
use App\Entity\Data\Weight;
use App\Entity\Food;
use App\Entity\User\Client;
use App\Entity\User\User;
use App\Enum\Nutrition\MealTypeEnum;
use App\Enum\User\GenderEnum;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const PASSWORD = 'password';

    private Generator       $faker;
    private ObjectManager   $manager;
    private DateTime        $today;
    private ArrayCollection $commonActivities;
    private ArrayCollection $commonFoods;
    private string          $todayFormated;
    private array           $genders;
    private array           $mealTypes;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->today = new DateTime('now');
        $this->todayFormated = ( new DateTime('now'))->format('Y-m-d');
        $this->commonActivities = new ArrayCollection();
        $this->commonFoods = new ArrayCollection();
        $this->genders = [GenderEnum::MAN, GenderEnum::WOMAN, GenderEnum::NON_BINARY];
        $this->mealTypes = [MealTypeEnum::DINNER, MealTypeEnum::SNACK, MealTypeEnum::LUNCH, MealTypeEnum::BREAKFAST];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        // TODO : Objectuve

        $this->commonActivities();
        $this->commonFoods();
        $this->users();
    }

    private function commonActivities(): void
    {
        $activities = [
          ['label' => 'Tennis', 'heartRate' => 160, 'isDistance' => false],
          ['label' => 'Football', 'heartRate' => 130, 'isDistance' => false],
          ['label' => 'Course à pied', 'heartRate' => 140, 'isDistance' => true],
          ['label' => 'Escalade', 'heartRate' => 150, 'isDistance' => false],
          ['label' => 'Judo', 'heartRate' => 150, 'isDistance' => false],
          ['label' => 'Boxe', 'heartRate' => 180, 'isDistance' => false],
          ['label' => 'Basket', 'heartRate' => 170, 'isDistance' => false],
          ['label' => 'Natation', 'heartRate' => 160, 'isDistance' => true],
          ['label' => 'Velo', 'heartRate' => 150, 'isDistance' => true],
        ];

        foreach ($activities as $activityData) {
           $activity = (new Activity())
            ->setLabel($activityData['label'])
            ->setHeartRate($activityData['heartRate'])
            ->setIsDistance($activityData['isDistance']);

           $this->manager->persist($activity);
           $this->commonActivities->add($activity);
        }

        $this->manager->flush();
    }

    private function commonFoods(): void
    {
        $foodLabels = [
            'Chocolat', 'Champignon', 'Carotte', 'Saumon' , 'Miel', 'Pain', 'Fromage de chèvre', 'Poire', 'Steack haché', 'Pates', 'Riz'
        ];

        foreach ($foodLabels as $foodLabel) {
            $food = (new Food())
                ->setLabel($foodLabel)
                ->setCalories(random_int(10, 400))
                ->setCarbohydrates(random_int(0, 100))
                ->setLipids(random_int(0, 100))
                ->setProteins(random_int(0, 100));

            $this->manager->persist($food);
            $this->commonFoods->add($food);
        }

        $this->manager->flush();
    }

    private function users(): void
    {
        $superAdmin = new User();
        $superAdmin
            ->setEnabled(true)
            ->setPassword($this->userPasswordHasher->hashPassword($superAdmin, self::PASSWORD))
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setEmail('admin@mail.fr')
            ->setFirstname('Super')
            ->setLastname('Admin')
            ->setGender(GenderEnum::MAN);

        $this->manager->persist($superAdmin);
        $this->manager->flush();

        for ($i = 1; $i <= 2; $i++) {
            shuffle($this->genders);
            $user = new User();
            $user
                ->setEnabled(true)
                ->setPassword($this->userPasswordHasher->hashPassword($user, self::PASSWORD))
                ->setEmail("client$i@mail.fr")
                ->setFirstname($this->faker->firstName)
                ->setLastname($this->faker->lastName)
                ->setGender($this->genders[0]);

            $this->manager->persist($user);
            $this->manager->flush();

            $this->client($user);
        }
    }

    private function client(User $user): void
    {
        $client = (new Client())
            ->setHeight(random_int(145,205))
            ->setBirthdate($this->faker->dateTimeBetween(new DateTime('now -60 years'), new DateTime('now -16 years')))
            ->setRegisteredAt(new DateTime('now -8months'))
        ;

        $user->setClient($client);

        $this->manager->persist($client);
        $this->manager->flush();

        // Activity et food
        for ($i = 1; $i <= 3; $i++) {
            $this->clientActivity($client, $i);
            $this->clientFood($client, $i);
        }

        // Daily report
        $dailyReportDate = $client->getRegisteredAt();
        $weight = random_int(40, 150);

        while($dailyReportDate->format('Y-m-d') != $this->todayFormated) {
            switch (random_int(0,2)) {
                case 0:
                    $weight += 0.4; // Le client a grossi
                    break;
                case 1: // Le client a maigri
                    $weight -= 0.4;
                case 2:
                    // Le poids du client n'a pas changé
            }
            $this->clientDailyReport($client, $dailyReportDate, $weight);
            $dailyReportDate->modify('+1 day');
        }
    }

    private function clientDailyReport(Client $client, DateTime $date, float $weight): void
    {
        $dailyReport = (new DailyReport())
            ->setClient($client)
            ->setDate($date);

        // Poids
        $weight = random_int(0,5) > 1 ? $weight : null; // Est ce que le client a penser à saisir sont poid ?
        if ($weight !== null) {
            $weight = (new Weight())->setWeight($weight);
            $dailyReport->setWeight($weight);
            $this->manager->persist($weight);
        }

        $this->manager->persist($dailyReport);
        $this->manager->flush();
        $this->manager->refresh($client);

        // Nutrition
        $nbNutrition = random_int(0, 6);
        if ($nbNutrition > 0) {
            $clientFoods = array_merge($client->getFoods()->toArray(), $this->commonFoods->toArray());
        }
        for ($i = 0; $i < $nbNutrition; $i++) {
            $this->clientNutrition($dailyReport, $clientFoods);
        }

        // Nuit de sommeille
        if (random_int(0,5) > 1) { // Est ce que le client a pensé à saisir son temps de someille
            $this->clientSleepTime($dailyReport);
        }

        // Activité
        $nbActivity = random_int(0, 3);
        if ($nbActivity > 0) {
            $clientActivities = array_merge($client->getActivities()->toArray(), $this->commonActivities->toArray());
        }
        for ($i = 0; $i < $nbActivity; $i++) { // Est ce que le client a pensé à saisir une activité
            $this->clientActivityTime($dailyReport, $clientActivities);
        }
    }

    public function clientActivityTime(DailyReport $dailyReport, array $activities)
    {
        shuffle($activities);

        $dailyReportDate = $dailyReport->getDate();
        $midActivityDuration = random_int(2, 60);
        if ($midActivityDuration % 2 === 1) $midActivityDuration -= 1;

        /** @var Activity $activity */
        $activity = $activities[0];
        $activityTime = (new ActivityTime())
            ->setDailyReport($dailyReport)
            ->setActivity($activity)
            ->setStartAt((clone $dailyReportDate)->modify('+' . 600 + $midActivityDuration . ' minutes' ))
            ->setEndAt((clone $dailyReportDate)->modify('+' . 600 + $midActivityDuration * 2 . ' minutes' ))
            ->setDistance($activity->isIsDistance() ? random_int(1, $midActivityDuration * 100): null);

        $this->manager->persist($activityTime);
        $this->manager->flush();
    }

    public function clientNutrition(DailyReport $dailyReport, array $foods)
    {
        shuffle($this->mealTypes);
        shuffle($foods);

        $nutrition = (new Nutrition())
            ->setDailyReport($dailyReport)
            ->setFood($foods[0])
            ->setFoodWeight(random_int(50,1000))
            ->setMealType($this->mealTypes[0]);

        $this->manager->persist($nutrition);
        $this->manager->flush();
    }

    private function clientSleepTime(DailyReport $dailyReport)
    {
        $midSleepTime = random_int(120,360);
        if ($midSleepTime % 2 === 1) $midSleepTime -= 1;

        $asleepAt = ((clone $dailyReport->getDate())->modify('-' . $midSleepTime . ' minutes'));
        $awakeAt = ((clone $dailyReport->getDate())->modify('+' . $midSleepTime . ' minutes'));

        $sleepTime = (new SleepTime())
            ->setDailyReport($dailyReport)
            ->setAsleepAt($asleepAt)
            ->setAwakeAt($awakeAt)
        ;

        $this->manager->persist($sleepTime);
        $this->manager->flush();
    }

    private function clientActivity(Client $client, int $number): void
    {
        $activity = (new Activity())
            ->setClient($client)
            ->setLabel('Mon activité ' . $number)
            ->setHeartRate(random_int(140, 180))
            ->setIsDistance(random_int(0,1) === 1);

        $this->manager->persist($activity);
        $this->manager->flush();
    }

    private function clientFood(Client $client, int $number): void
    {
        $food = (new Food())
            ->setClient($client)
            ->setLabel('Mon aliment ' . $number)
            ->setProteins(random_int(0, 100))
            ->setLipids(random_int(0, 100))
            ->setCarbohydrates(random_int(0, 100))
            ->setCalories(random_int(10, 400));

        $this->manager->persist($food);
        $this->manager->flush();
    }
}
