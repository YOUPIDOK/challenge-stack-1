<?php

namespace App\Controller\Main\Data;

use App\Entity\DailyReport;
use App\Entity\Data\SleepTime;
use App\Form\Data\SleepTimeType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\SleepTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/sleep/time')]
class SleepTimeController extends AbstractController
{
    #[Route('/', name: 'app_main_data_sleep_time_index', methods: ['GET'])]
    public function index(Request $request, SleepTimeRepository $sleepTimeRepository): Response
    {
        $sleepTimes = $sleepTimeRepository->findBy(['dailyReport.client.id' => $request->getUser()->getClient()->getId()]);
        return $this->render('pages/data/sleep_time/index.html.twig', [
            'sleep_times' => $sleepTimes,
        ]);
    }

    #[Route('/new/{id}', name: 'app_main_data_sleep_time_new', methods: ['GET', 'POST'])]
    public function new(DailyReport $dailyReport,Request $request, SleepTimeRepository $sleepTimeRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $sleepTime = new SleepTime();
        $sleepTime->setDailyReport($dailyReport);
        $form = $this->createForm(SleepTimeType::class, $sleepTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sleepTime->setTimeFromDates();

            $sleepTimeRepository->save($sleepTime, true);
            $dailyReportRepository->save($dailyReport, true);

            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/sleep_time/new.html.twig', [
            'sleep_time' => $sleepTime,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_sleep_time_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SleepTime $sleepTime, SleepTimeRepository $sleepTimeRepository): Response
    {
        $form = $this->createForm(SleepTimeType::class, $sleepTime);
        $form->handleRequest($request);
        $dailyReport = $sleepTime->getDailyReport();

        if ($form->isSubmitted() && $form->isValid()) {
            $sleepTimeRepository->save($sleepTime, true);

            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/sleep_time/edit.html.twig', [
            'sleep_time' => $sleepTime,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_sleep_time_delete', methods: ['POST'])]
    public function delete(Request $request, SleepTime $sleepTime, SleepTimeRepository $sleepTimeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sleepTime->getId(), $request->request->get('_token'))) {
            $sleepTimeRepository->remove($sleepTime, true);
        }

        return $this->redirectToRoute('app_main_data_sleep_time_index', [], Response::HTTP_SEE_OTHER);
    }
}
