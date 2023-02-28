<?php

namespace App\Controller\Main;

use App\Entity\DailyReport;
use App\Repository\DailyReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/daily-report/nutrition')]
class NutritionController extends AbstractController
{
    // Create
    // Edition
    // Supprimer


    #[Route('/new', name: 'daily_report_nutrition_new')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $dailyReport = new DailyReport();
        $dailyReport->setDate(new \DateTime());
        $dailyReport->setClient($this->getUser()->getClient());
        $entityManager->persist($dailyReport);
        $entityManager->flush();

        return $this->render('dailyReport/show.html.twig', [
            'dailyReport' => $dailyReport,
        ]);
    }
}
