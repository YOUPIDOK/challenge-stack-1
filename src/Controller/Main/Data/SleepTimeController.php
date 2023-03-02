<?php

namespace App\Controller\Main\Data;

use App\Entity\DailyReport;
use App\Entity\Data\SleepTime;
use App\Form\Data\SleepTimeType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\SleepTimeRepository;
use App\Security\Main\Voter\DailyReportVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/sleep/time')]
#[IsGranted('ROLE_CLIENT')]
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
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $sleepTime = new SleepTime();
        $sleepTime->setDailyReport($dailyReport);
        $form = $this->createForm(SleepTimeType::class, $sleepTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sleepTimeRepository->save($sleepTime, true);
            $dailyReportRepository->save($dailyReport, true);
            $this->addFlash('success', 'Votre temps de sommeil a été ajouté.');
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
        $dailyReport = $sleepTime->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $form = $this->createForm(SleepTimeType::class, $sleepTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sleepTimeRepository->save($sleepTime, true);

            $this->addFlash('success', 'Votre temps de sommeil a été modifié.');
            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/sleep_time/edit.html.twig', [
            'sleep_time' => $sleepTime,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_sleep_time_delete', methods: ['GET'])]
    public function delete(Request $request, SleepTime $sleepTime, SleepTimeRepository $sleepTimeRepository, EntityManagerInterface $em): Response
    {
        $dailyReport = $sleepTime->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $sleepTimeRepository->remove($sleepTime, true);
        $this->addFlash('success', 'Votre temps de sommeil a été supprimé.');

        $dailyReport->updateDailyReportSleepTime();
        $em->flush();

        return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
    }
}
