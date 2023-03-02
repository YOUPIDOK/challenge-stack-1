<?php

namespace App\Controller\Main;

use App\Form\DashboardFilterType;
use App\Repository\Data\WeightRepository;
use App\Services\ChartBuilder;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_CLIENT')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(
        WeightRepository $weightRepo,
        ChartBuilder $chartBuilder,
        Request $request
    ): Response
    {
        $client = $this->getUser()->getClient();
        $weight = $weightRepo->findLastWeightByClient($client);

        $dateFilter = ['start' => $client->getRegisteredAt(), 'end' => new DateTime('now')];
        $form = $this->createForm(DashboardFilterType::class, $dateFilter, ['client' => $client]);
        $form->handleRequest($request);

        $weights = $weightRepo->searchByClient($client, $form->get('start')->getData(), $form->get('end')->getData());
        $weightChart = $chartBuilder->generate(ChartBuilder::LINE_TYPE, 'Évolution du poids (kg)', $weights, $form->get('start')->getData());

        // TODO : label
        // TODO : Nutrition
        // TODO : Sommeil
        // TODO : Activité

        return $this->render('pages/dashboard.html.twig', [
            'client' => $client,
            'weight' => $weight,
            'weightChart' => $weightChart,
            'form' => $form->createView()
        ]);
    }
}
