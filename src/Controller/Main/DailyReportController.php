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
        $user = $this->getUser();
        if (isset($user)) {
            $client = $user->getClient();
            $dailyReports = $client->getDailyReports();

            return $this->render('pages/dailyReport/index.html.twig', [
                'dailyReports' => $dailyReports,
            ]);
        }
        // Pas connecté :
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/', name: 'daily_report_show_current')]
    public function showOrCreateCurrent(Request $request, DailyReportRepository $dailyReportRepository): Response
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

            $request->getSession()->set('current_daily_report_id', $dailyReport->getId());
            return $this->render('pages/dailyReport/show.html.twig' ,
            [
                'dailyReports' => $dailyReport
            ]);
        }

        // Pas connecté :
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/{id}', name: 'daily_report_show', methods: ['GET'])]
    public function show(Request $request, DailyReport $dailyReport): Response
    {
        $request->getSession()->set('current_daily_report_id', $dailyReport->getId());
        return $this->render('pages/dailyReport/show.html.twig', [
            'dailyReport' => $dailyReport,
        ]);
    }
}
