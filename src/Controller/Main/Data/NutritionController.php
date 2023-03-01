<?php

namespace App\Controller\Main\Data;

use App\Entity\Data\Nutrition;
use App\Form\Data\NutritionType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\NutritionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/data/nutrition')]
class NutritionController extends AbstractController
{
    #[Route('/', name: 'app_data_nutrition_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user !== null) {
            $client = $user->getClient();

            return $this->render('pages/data/nutrition/index.html.twig', [
                'nutrition' => $client->getNutritions(),
            ]);
        }
        return $this->render('pages/homepage.html.twig');
    }

    #[Route('/new', name: 'app_data_nutritions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NutritionRepository $nutritionsRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $id = $request->getSession()->get('current_daily_report_id');
        $dailyReport = $dailyReportRepository->find($id);

        $nutrition = new Nutrition();
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            if ($user !== null && $dailyReport !== null) {
                $client = $user->getClient();
                $dailyReport->setClient($client);
                $nutrition->setClient($client);
                $nutrition->setDate(new \DateTime());

                $dailyReport->addNutrition($nutrition);
                $nutritionsRepository->save($nutrition, true);
                $dailyReportRepository->save($dailyReport, true);
            }

            return $this->redirectToRoute('daily_report_show_current', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/new.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_nutrition_show', methods: ['GET'])]
    public function show(Nutrition $nutrition): Response
    {
        return $this->render('pages/data/nutrition/show.html.twig', [
            'nutrition' => $nutrition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_nutrition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nutrition $nutrition, NutritionsRepository $nutritionsRepository): Response
    {
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionsRepository->save($nutrition, true);

            return $this->redirectToRoute('app_data_nutrition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/edit.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_nutrition_delete', methods: ['POST'])]
    public function delete(Request $request, Nutrition $nutrition, NutritionsRepository $nutritionsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nutrition->getId(), $request->request->get('_token'))) {
            $nutritionsRepository->remove($nutrition, true);
        }

        return $this->redirectToRoute('app_data_nutrition_index', [], Response::HTTP_SEE_OTHER);
    }
}
