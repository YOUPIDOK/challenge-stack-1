<?php

namespace App\Controller\Main\Data;

use App\Entity\Data\Nutrition;
use App\Form\Data\NutritionType;
use App\Repository\Data\NutritionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/data/nutrition')]
class NutritionController extends AbstractController
{
    #[Route('/', name: 'app_main_data_nutrition_index', methods: ['GET'])]
    public function index(NutritionRepository $nutritionRepository): Response
    {
        return $this->render('pages/data/nutrition/index.html.twig', [
            'nutrition' => $nutritionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_main_data_nutrition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NutritionRepository $nutritionRepository): Response
    {
        $nutrition = new Nutrition();
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionRepository->save($nutrition, true);

            return $this->redirectToRoute('app_main_data_nutrition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/new.html.twig', [
            'nutrition' => $nutrition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_data_nutrition_show', methods: ['GET'])]
    public function show(Nutrition $nutrition): Response
    {
        return $this->render('pages/data/nutrition/show.html.twig', [
            'nutrition' => $nutrition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_data_nutrition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nutrition $nutrition, NutritionRepository $nutritionRepository): Response
    {
        $form = $this->createForm(NutritionType::class, $nutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nutritionRepository->save($nutrition, true);

            return $this->redirectToRoute('app_main_data_nutrition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/data/nutrition/edit.html.twig', [
            'nutrition' => $nutrition,
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
