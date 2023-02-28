<?php

namespace App\Controller\Main;

use App\Entity\DailyReport;
use App\Repository\DailyReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/daily-report')]
class DailyReportController extends AbstractController
{
    #[Route('/', name: 'daily_report_index')]
    public function index(DailyReportRepository $dailyReportRepository): Response
    {
        $user = $this->getUser();
        if (isset($user)) {
            $idClient = $user->getClient()->getId();
            $dailyReports = $dailyReportRepository->findBy(['client.id' => $idClient]);
            dump($dailyReports);
            return $this->render('pages/homepage.html.twig' ,
            [
                'dailyReports' => $dailyReports
            ]);
        }
        // Pas connecté :
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/{id}', name: 'daily_report_show', methods: ['POST'])]
    public function show(DailyReport $dailyReport): Response
    {
        return $this->render('dailyReport/show.html.twig', [
            'dailyReport' => $dailyReport,
        ]);
    }

    #[Route('/new', name: 'daily_report_new')]
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
