<?php

namespace App\Controller\Main\Data;

use App\Entity\DailyReport;
use App\Entity\Data\Nutrition;
use App\Form\Data\NutritionType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\NutritionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/nutrition')]
class NutritionController extends AbstractController
{
    #[Route('/', name: 'app_main_data_nutrition_index', methods: ['GET'])]
    public function index(Request $request, NutritionRepository $nutritionRepository): Response
    {
        $nutritions = $nutritionRepository->findBy(['dailyReport.client.id' => $request->getUser()->getClient()->getId()]);
        return $this->render('pages/data/nutrition/index.html.twig', [
            'nutritions' => $nutritions,
        ]);
    }

    #[Route('/new/{id}', name: 'app_main_data_nutrition_new', methods: ['GET', 'POST'])]
    public function new(DailyReport $dailyReport,Request $request, NutritionRepository $nutritionRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $nutrition = new Nutrition();
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyReport->addNutrition($nutrition);

            $nutritionRepository->save($nutrition, true);
            $dailyReportRepository->save($dailyReport, true);

            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/new.html.twig', [
            'nutrition' => $nutrition,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_main_data_nutrition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nutrition $nutrition, NutritionRepository $nutritionRepository): Response
    {
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);
        $dailyReport = $nutrition->getDailyReport();

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionRepository->save($nutrition, true);

            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/edit.html.twig', [
            'nutrition' => $nutrition,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_nutrition_delete', methods: ['POST'])]
    public function delete(Request $request, Nutrition $nutrition, NutritionRepository $nutritionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nutrition->getId(), $request->request->get('_token'))) {
            $nutritionRepository->remove($nutrition, true);
        }

        return $this->redirectToRoute('app_main_data_nutrition_index', [], Response::HTTP_SEE_OTHER);
    }
}
