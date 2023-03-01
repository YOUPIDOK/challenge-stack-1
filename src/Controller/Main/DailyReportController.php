<?php

namespace App\Controller\Main;

use App\Entity\DailyReport;
use App\Repository\DailyReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/daily-report')]
class DailyReportController extends AbstractController
{
    #[Route('/', name: 'daily_report_index', methods: ['GET'])]
    public function index(): Response
    {
        $client = $this->getUser()->getClient();
        $dailyReports = $client->getDailyReports();

        return $this->render('pages/dailyReport/index.html.twig', [
                'dailyReports' => $dailyReports,
            ]);
    }

    #[Route('/current', name: 'daily_report_show_current')]
    public function showOrCreateCurrent(Request $request, DailyReportRepository $dailyReportRepository): Response
    {
        $client = $this->getUser()->getClient();
        $dailyReport = $client->getCurrentDailyReport();

        if ($dailyReport === null) {
            $dailyReport = new DailyReport();
            $dailyReport->setDate(new \DateTime());
            $dailyReport->setClient($this->getUser()->getClient());
            $dailyReportRepository->save($dailyReport, true);
        }

        return $this->render('pages/dailyReport/show.html.twig' , [
            'dailyReport' => $dailyReport
        ]);
    }

    #[Route('/{id}', name: 'daily_report_show', methods: ['GET'])]
    public function show(Request $request, DailyReport $dailyReport): Response
    {
        return $this->render('pages/dailyReport/show.html.twig', [
            'dailyReport' => $dailyReport,
        ]);
    }
}
