<?php

namespace App\Controller\Main\Data;

use App\Entity\DailyReport;
use App\Entity\Data\ActivityTime;
use App\Form\Data\ActivityTimeType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\ActivityTimeRepository;
use App\Repository\Data\WeightRepository;
use App\Security\Main\Voter\DailyReportVoter;
use App\Security\Main\Voter\FoodVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/activity/time')]
#[IsGranted('ROLE_CLIENT')]
class ActivityTimeController extends AbstractController
{
    #[Route('/', name: 'app_main_data_activity_time_index', methods: ['GET'])]
    public function index(Request $request, ActivityTimeRepository $activityTimeRepository): Response
    {
        $activity_times = $activityTimeRepository->findBy(['dailyReport.client.id' => $request->getUser()->getClient()->getId()]);
        return $this->render('pages/data/activity_time/index.html.twig', [
            'activity_times' => $activity_times,
        ]);
    }

    #[Route('/new/{id}', name: 'app_main_data_activity_time_new', methods: ['GET', 'POST'])]
    public function new(DailyReport $dailyReport, Request $request, ActivityTimeRepository $activityTimeRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $activityTime = new ActivityTime();
        $activityTime->setDailyReport($dailyReport);
        $form = $this->createForm(ActivityTimeType::class, $activityTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityTimeRepository->save($activityTime, true);
            $dailyReportRepository->save($dailyReport, true);
            $this->addFlash('success', 'Votre activit?? du jour a ??t?? ajout??e.');
            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/activity_time/new.html.twig', [
            'activity_time' => $activityTime,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_activity_time_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActivityTime $activityTime, ActivityTimeRepository $activityTimeRepository): Response
    {
        $dailyReport = $activityTime->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $form = $this->createForm(ActivityTimeType::class, $activityTime);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $activityTimeRepository->save($activityTime, true);

            $this->addFlash('success', 'Votre activit?? du jour a ??t?? modifi??e.');
            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/activity_time/edit.html.twig', [
            'activity_time' => $activityTime,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_activity_time_delete', methods: ['GET'])]
    public function delete(Request $request, ActivityTime $activityTime, ActivityTimeRepository $activityTimeRepository, EntityManagerInterface $em, WeightRepository $weightRepository): Response
    {
        $dailyReport = $activityTime->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $activityTimeRepository->remove($activityTime, true);
        $this->addFlash('success', 'Votre activit?? du jour a ??t?? supprim??e.');

        $dailyReport->updateDailyActivityTime($weightRepository->findLastWeightByClient($dailyReport->getClient()));
        $em->flush();

        return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
    }
}
