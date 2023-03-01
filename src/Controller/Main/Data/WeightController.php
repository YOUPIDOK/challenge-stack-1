<?php

namespace App\Controller\Main\Data;

use App\Entity\DailyReport;
use App\Entity\Data\Weight;
use App\Form\Data\WeightType;
use App\Repository\DailyReportRepository;
use App\Repository\Data\WeightRepository;
use App\Security\Main\Voter\DailyReportVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/weight')]
#[IsGranted('ROLE_CLIENT')]
class WeightController extends AbstractController
{
    #[Route('/', name: 'app_main_data_weight_index', methods: ['GET'])]
    public function index(Request $request, WeightRepository $weightRepository): Response
    {
        $weights = $weightRepository->findBy(['dailyReport.client.id' => $request->getUser()->getClient()->getId()]);
        return $this->render('pages/data/weight/index.html.twig', [
            'weights' => $weights,
        ]);
    }

    #[Route('/new/{id}', name: 'app_main_data_weight_new', methods: ['GET', 'POST'])]
    public function new(DailyReport $dailyReport,Request $request, WeightRepository $weightRepository, DailyReportRepository $dailyReportRepository): Response
    {
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $weight = new Weight();
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyReport->setWeight($weight);
            $weightRepository->save($weight, true);
            $dailyReportRepository->save($dailyReport, true);
            $this->addFlash('success', 'Votre poid du jour à était ajouté');
            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/weight/new.html.twig', [
            'weight' => $weight,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_weight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weight $weight, WeightRepository $weightRepository): Response
    {
        $dailyReport = $weight->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weightRepository->save($weight, true);
            $this->addFlash('success', 'Votre poid du jour à était modifié');
            return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/weight/edit.html.twig', [
            'weight' => $weight,
            'dailyReport' => $dailyReport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_weight_delete', methods: ['GET'])]
    public function delete(Request $request, Weight $weight, WeightRepository $weightRepository): Response
    {
        $dailyReport = $weight->getDailyReport();
        $this->denyAccessUnlessGranted(DailyReportVoter::ACCESS, $dailyReport);

        $weightRepository->remove($weight, true);
        $this->addFlash('success', 'Votre poid du jour à était supprimé');

        return $this->redirectToRoute('daily_report_show', ['id' => $dailyReport->getId()], Response::HTTP_SEE_OTHER);
    }
}
