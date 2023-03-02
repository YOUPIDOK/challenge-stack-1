<?php

namespace App\Controller\Main\Data;

use App\Entity\DailyReport;
use App\Entity\Data\Nutrition;
use App\Form\Data\NutritionType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\NutritionRepository;
use App\Security\Main\Voter\DailyReportVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/nutrition')]
#[IsGranted('ROLE_CLIENT')]
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

    #[Route('/navbnew/{id}', name: 'app_main_data_nutrition_new', methods: ['GET', 'POST'])]
    public function new(DailyReport $dailyReport,Request $request, NutritionRepository $nutritionRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $nutrition = new Nutrition();
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyReport->addNutrition($nutrition);

            $nutritionRepository->save($nutrition, true);
            $dailyReportRepository->save($dailyReport, true);
            $this->addFlash('success', 'Votre nutrition du jour a été ajoutée.');
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
        $dailyReport = $nutrition->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionRepository->save($nutrition, true);
            $this->addFlash('success', 'Votre nutrition du jour a été modifiée.');
            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/edit.html.twig', [
            'nutrition' => $nutrition,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_nutrition_delete', methods: ['GET'])]
    public function delete(Request $request, Nutrition $nutrition, NutritionRepository $nutritionRepository, EntityManagerInterface $em): Response
    {
        $dailyReport = $nutrition->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $nutritionRepository->remove($nutrition, true);
        $this->addFlash('success', 'Votre nutrition du jour a été supprimée.');

        $dailyReport->updateDailyNutrition();
        $em->flush();

        return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
    }
}
