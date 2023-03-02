<?php

namespace App\Controller\Main;

use App\Entity\DailyReport;
use App\Entity\Data\SleepTime;
use App\Form\DashboardFilterType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\SleepTimeRepository;
use App\Repository\Data\WeightRepository;
use App\Services\ChartBuilder;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_CLIENT')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(
        WeightRepository $weightRepo,
        DailyReportRepository $dailyReportRepo,
        Request $request
    ): Response
    {
        $client = $this->getUser()->getClient();
        $weight = $weightRepo->findLastWeightByClient($client);

        $dateFilter = ['start' =>  new DateTime('now -1 month'), 'end' => new DateTime('now')];
        $form = $this->createForm(DashboardFilterType::class, $dateFilter, ['client' => $client]);
        $form->handleRequest($request);

        $dailyReports = $dailyReportRepo->searchByClient($client, $form->get('start')->getData(), $form->get('end')->getData());
        $weights = $weightRepo->searchByClient($client, $form->get('start')->getData(), $form->get('end')->getData());

        $weightChart = (new ChartBuilder())->generate(ChartBuilder::WEIGHT_AVERAGE, 'Évolution du poids (kg/j)', $weights, $form->get('start')->getData());
        $sleepTimeChart = (new ChartBuilder())->generate(ChartBuilder::SLEEP_TIME_AVERAGE, 'Évolution du temps de sommeil (h/j)', $dailyReports, $form->get('start')->getData());
        $eatCaloriesChart = (new ChartBuilder())->generate(ChartBuilder::EAT_CALORIES, 'Apport calorique (Kcal/j)', $dailyReports, $form->get('start')->getData());
        $spentCaloriesChart = (new ChartBuilder())->generate(ChartBuilder::SPENT_CALORIES, 'Dépense calorique (Kcal/j)', $dailyReports, $form->get('start')->getData());

        return $this->renderForm('pages/dashboard.html.twig', [
            'client' => $client,
            'weight' => $weight,
            'weightChart' => $weightChart,
            'sleepTimeChart' => $sleepTimeChart,
            'eatCaloriesChart' => $eatCaloriesChart,
            'spentCaloriesChart' => $spentCaloriesChart,
            'form' => $form
        ]);
    }
}
