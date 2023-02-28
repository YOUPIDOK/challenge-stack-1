<?php

namespace App\Controller\Data;

use App\Entity\Data\Nutrition;
use App\Form\Data\NutritionsType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\NutritionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/data/nutritions')]
class NutritionsController extends AbstractController
{
    #[Route('/new', name: 'app_data_nutritions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NutritionsRepository $nutritionsRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $nutrition = new Nutrition();
        $form = $this->createForm(NutritionsType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $this->getUser()->getClient();

            $dailyReport = $client->getCurrentDailyReport();
            if ($dailyReport !== null) {
                // Ajout des propriétés hors formulaire
                $nutrition->setClient($client);
                $nutrition->setDate($dailyReport->getDate());
                $nutritionsRepository->save($nutrition, true);

                $dailyReport->addNutrition($nutrition);
                $dailyReportRepository->save($dailyReport, true);
            }

            return $this->redirectToRoute('daily_report_show_current', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('data/nutritions/new.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_nutritions_show', methods: ['GET'])]
    public function show(Nutrition $nutrition): Response
    {
        return $this->render('data/nutritions/show.html.twig', [
            'nutrition' => $nutrition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_data_nutritions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nutrition $nutrition, NutritionsRepository $nutritionsRepository): Response
    {
        $form = $this->createForm(NutritionsType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionsRepository->save($nutrition, true);

            return $this->redirectToRoute('app_data_nutritions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('data/nutritions/edit.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_data_nutritions_delete', methods: ['POST'])]
    public function delete(Request $request, Nutrition $nutrition, NutritionsRepository $nutritionsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nutrition->getId(), $request->request->get('_token'))) {
            $nutritionsRepository->remove($nutrition, true);
        }

        return $this->redirectToRoute('app_data_nutritions_index', [], Response::HTTP_SEE_OTHER);
    }
}
