<?php

namespace App\Controller\Main;

use App\Entity\Objective\Objective;
use App\Form\Objective\ObjectiveType;
use App\Repository\Objective\ObjectiveRepository;
use App\Security\Main\Voter\DailyReportVoter;
use App\Security\Main\Voter\ObjectiveVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/main/objective')]
class ObjectiveController extends AbstractController
{
    #[Route('/', name: 'app_main_objective_index', methods: ['GET'])]
    public function index(Request $request, ObjectiveRepository $objectiveRepository): Response
    {
        $myObjectives = $objectiveRepository->findBy(['client.id' => $request->getUser()->getClient()->getId()]);
        return $this->render('main/objective/index.html.twig', [
            'myObjectives' => $myObjectives,
        ]);
    }


    #[Route('/new', name: 'app_main_objective_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ObjectiveRepository $objectiveRepository): Response
    {
        $objective = new Objective();
        $form = $this->createForm(ObjectiveType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectiveRepository->save($objective, true);

            return $this->redirectToRoute('app_main_objective_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/objective/new.html.twig', [
            'objective' => $objective,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_objective_show', methods: ['GET'])]
    public function show(Objective $objective): Response
    {
        $this->denyAccessUnlessGranted(ObjectiveVoter::ACCESS, $objective);

        return $this->render('pages/objective/show.html.twig', [
            'objective' => $objective,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_main_objective_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objective $objective, ObjectiveRepository $objectiveRepository): Response
    {
        $this->denyAccessUnlessGranted(ObjectiveVoter::ACCESS, $objective);

        $form = $this->createForm(ObjectiveType::class, $objective);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectiveRepository->save($objective, true);

            return $this->redirectToRoute('app_main_objective_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/objective/edit.html.twig', [
            'objective' => $objective,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_main_objective_delete', methods: ['GET'])]
    public function delete(Request $request, Objective $objective, ObjectiveRepository $objectiveRepository): Response
    {
        $this->denyAccessUnlessGranted(ObjectiveVoter::ACCESS, $objective);

        $objectiveRepository->remove($objective, true);
        $this->addFlash('success', 'Votre objectif à était supprimé');

        return $this->redirectToRoute('app_main_objective_index', [], Response::HTTP_SEE_OTHER);
    }
}
