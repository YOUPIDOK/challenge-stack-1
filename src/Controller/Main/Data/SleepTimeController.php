<?php

namespace App\Controller\Main\Data;

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
    public function index(): Response
    {
        $client = $this->getUser()->getClient();

        return $this->render('main/data/sleep_time/index.html.twig', [
            'sleep_times' => $client->getSleepTimes(),
        ]);
    }

    #[Route('/new', name: 'app_main_data_sleep_time_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SleepTimeRepository $sleepTimeRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $id = $request->getSession()->get('current_daily_report_id');
        $dailyReport = $dailyReportRepository->find($id);

        $sleepTime = new SleepTime();
        $form = $this->createForm(SleepTimeType::class, $sleepTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($dailyReport !== null) {
                $client = $this->getUser()->getClient();
                $sleepTime->setClient($client);
                $sleepTime->setTimeFromDates();
                $dailyReport->setClient($client);


                $dailyReport->addSleepTime($sleepTime);
                $sleepTimeRepository->save($sleepTime, true);
                $dailyReportRepository->save($dailyReport, true);
            }

            return $this->redirectToRoute('app_main_data_sleep_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/data/sleep_time/new.html.twig', [
            'sleep_time' => $sleepTime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_sleep_time_show', methods: ['GET'])]
    public function show(SleepTime $sleepTime): Response
    {
        return $this->render('main/data/sleep_time/show.html.twig', [
            'sleep_time' => $sleepTime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_sleep_time_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SleepTime $sleepTime, SleepTimeRepository $sleepTimeRepository): Response
    {
        $form = $this->createForm(SleepTimeType::class, $sleepTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sleepTimeRepository->save($sleepTime, true);

            return $this->redirectToRoute('app_main_data_sleep_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/data/sleep_time/edit.html.twig', [
            'sleep_time' => $sleepTime,
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
