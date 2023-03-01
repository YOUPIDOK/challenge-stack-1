<?php

namespace App\Controller\Main\Data;

use App\Entity\Data\Weight;
use App\Form\Data\WeightType;
use App\Repository\Data\WeightRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/weight')]
class WeightController extends AbstractController
{
    #[Route('/', name: 'app_main_data_weight_index', methods: ['GET'])]
    public function index(WeightRepository $weightRepository): Response
    {
        return $this->render('pages/data/weight/index.html.twig', [
            'weights' => $weightRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_main_data_weight_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WeightRepository $weightRepository): Response
    {
        $weight = new Weight();
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weightRepository->save($weight, true);

            return $this->redirectToRoute('app_main_data_weight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/weight/new.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_weight_show', methods: ['GET'])]
    public function show(Weight $weight): Response
    {
        return $this->render('pages/data/weight/show.html.twig', [
            'weight' => $weight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_weight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Weight $weight, WeightRepository $weightRepository): Response
    {
        $form = $this->createForm(WeightType::class, $weight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weightRepository->save($weight, true);

            return $this->redirectToRoute('app_main_data_weight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/weight/edit.html.twig', [
            'weight' => $weight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_weight_delete', methods: ['POST'])]
    public function delete(Request $request, Weight $weight, WeightRepository $weightRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weight->getId(), $request->request->get('_token'))) {
            $weightRepository->remove($weight, true);
        }

        return $this->redirectToRoute('app_main_data_weight_index', [], Response::HTTP_SEE_OTHER);
    }
}
