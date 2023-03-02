<?php

namespace App\Command\Main;

use App\Entity\Objective\Objective;
use App\Repository\Objective\ObjectiveRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:objectives-expiration',
    description: 'Fais expirer les objectifs des utilisateurs lorsqu\'ils sont terminé'
)]
class ObjectiveExpirationCommand extends Command
{
    /**
     * @var ObjectiveRepository
     */
    private ObjectiveRepository $objectiveRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        ObjectiveRepository $objectiveRepository,
        EntityManagerInterface $entityManager,
        string $name = null
    ) {
        $this->objectiveRepository = $objectiveRepository;
        $this->entityManager = $entityManager;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['[' . date('d/m/Y-H:s') . '] Début de la commande']);

        $output->writeln(['[' . date('d/m/Y-H:s') . '] Récupération des objectifs expirer']);
        $expiredObjectives = $this->objectiveRepository->findObjectivesExpired();

        foreach ($expiredObjectives as $objective) {
            /** @var Objective $objective */
            $objective->setActive(false);
            $this->objectiveRepository->save($objective, false);
        }
        $this->entityManager->flush();

        $output->writeln(['[' . date('d/m/Y-H:s') . '] Fin commande, Nombre d\'objectifs mis à jour : ' . count($expiredObjectives)]);

        return Command::SUCCESS;
    }
}
