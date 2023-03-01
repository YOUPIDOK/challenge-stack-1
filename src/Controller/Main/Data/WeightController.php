<?php

namespace App\Controller\Main\Data;

use App\Entity\Data\Weight;
use App\Form\Data\WeightType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\WeightsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/weight')]
class WeightController extends AbstractController
{
    #[Route('/', name: 'app_main_data_weight_index', methods: ['GET'])]
    public function index(): Response
    {
        $client = $this->getUser()->getClient();

        return $this->render('main/data/weight/index.html.twig', [
            'weights' => $client->getWeights(),
        ]);
    }

    #[Route('/new', name: 'app_main_data_weight_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WeightsRepository $weightsRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $id = $request->getSession()->get('current_daily_report_id');
        $dailyReport = $dailyReportRepository->find($id);

        $weight = new Weight();
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($dailyReport !== null) {
                $client = $this->getUser()->getClient();
                $weight->setClient($client);
                $dailyReport->setClient($client);
                $weight->setDate(new \DateTime());

                $dailyReport->setWeight($weight);
                $weightsRepository->save($weight, true);
                $dailyReportRepository->save($dailyReport, true);
            }


            return $this->redirectToRoute('app_main_data_weight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/data/weight/new.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_weight_show', methods: ['GET'])]
    public function show(Weight $weight): Response
    {
        return $this->render('main/data/weight/show.html.twig', [
            'weight' => $weight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_weight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weight $weight, WeightsRepository $weightsRepository): Response
    {
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weightsRepository->save($weight, true);

            return $this->redirectToRoute('app_main_data_weight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('main/data/weight/edit.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_weight_delete', methods: ['POST'])]
    public function delete(Request $request, Weight $weight, WeightsRepository $weightsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weight->getId(), $request->request->get('_token'))) {
            $weightsRepository->remove($weight, true);
        }

        return $this->redirectToRoute('app_main_data_weight_index', [], Response::HTTP_SEE_OTHER);
    }
}
