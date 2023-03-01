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
    #[Route('/', name: 'daily_report_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        if (isset($user)) {
            $client = $user->getClient();
            $dailyReport = $client->getDailyReports();


            return $this->render('pages/daily_report/show.html.twig', [
                'dailyReport' => $dailyReport,
            ]);
        }
        // Pas connecté :
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/', name: 'daily_report_show_current')]
    public function showOrCreateCurrent(DailyReportRepository $dailyReportRepository): Response
    {
        $user = $this->getUser();
        if (isset($user)) {
            $client = $user->getClient();
            $dailyReport = $client->getCurrentDailyReport();

            // Création du rapport du jour s'il n'existe pas
            if ($dailyReport === null) {
                $dailyReport = new DailyReport();
                $dailyReport->setDate(new \DateTime());
                $dailyReport->setClient($this->getUser()->getClient());
                $dailyReportRepository->save($dailyReport, true);
            }

            return $this->render('pages/daily_report/show.html.twig' ,
            [
                'dailyReports' => $dailyReport
            ]);
        }

        // Pas connecté :
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/{id}', name: 'daily_report_show', methods: ['GET'])]
    public function show(DailyReport $dailyReport): Response
    {
        return $this->render('pages/daily_report/show.html.twig', [
            'dailyReport' => $dailyReport,
        ]);
    }
}
