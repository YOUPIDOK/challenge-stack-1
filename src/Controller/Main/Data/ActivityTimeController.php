<?php

namespace App\Controller\Main\Data;

use App\Entity\Data\ActivityTime;
use App\Form\Data\ActivityTimeType;
use App\Repository\Data\ActivityTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/activity/time')]
class ActivityTimeController extends AbstractController
{
    #[Route('/', name: 'app_main_data_activity_time_index', methods: ['GET'])]
    public function index(ActivityTimeRepository $activityTimeRepository): Response
    {
        return $this->render('pages/data/activity_time/index.html.twig', [
            'activity_times' => $activityTimeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_main_data_activity_time_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActivityTimeRepository $activityTimeRepository): Response
    {
        $activityTime = new ActivityTime();
        $form = $this->createForm(ActivityTimeType::class, $activityTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityTimeRepository->save($activityTime, true);

            return $this->redirectToRoute('app_main_data_activity_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/activity_time/new.html.twig', [
            'activity_time' => $activityTime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_activity_time_show', methods: ['GET'])]
    public function show(ActivityTime $activityTime): Response
    {
        return $this->render('pages/data/activity_time/show.html.twig', [
            'activity_time' => $activityTime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_activity_time_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ActivityTime $activityTime, ActivityTimeRepository $activityTimeRepository): Response
    {
        $form = $this->createForm(ActivityTimeType::class, $activityTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityTimeRepository->save($activityTime, true);

            return $this->redirectToRoute('app_main_data_activity_time_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/activity_time/edit.html.twig', [
            'activity_time' => $activityTime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_activity_time_delete', methods: ['POST'])]
    public function delete(Request $request, ActivityTime $activityTime, ActivityTimeRepository $activityTimeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activityTime->getId(), $request->request->get('_token'))) {
            $activityTimeRepository->remove($activityTime, true);
        }

        return $this->redirectToRoute('app_main_data_activity_time_index', [], Response::HTTP_SEE_OTHER);
    }
}
