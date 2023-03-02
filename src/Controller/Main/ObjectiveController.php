<?php

namespace App\Controller\Main;

use App\Entity\Objective\Objective;
use App\Form\Activity\SearchActivityType;
use App\Form\Objective\ObjectiveType;
use App\Form\Objective\SearchObjectiveType;
use App\Repository\Objective\ObjectiveRepository;
use App\Security\Main\Voter\DailyReportVoter;
use App\Security\Main\Voter\ObjectiveVoter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('objectifs')]
#[IsGranted('ROLE_CLIENT')]
class ObjectiveController extends AbstractController
{
    #[Route('/', name: 'app_main_objective_index', methods: ['GET'])]
    public function index(Request $request, ObjectiveRepository $objectiveRepository): Response
    {
        $form = $this->createForm(SearchObjectiveType::class);
        $form->handleRequest($request);

        $adapter = new QueryAdapter($objectiveRepository->searchObjectifQb($this->getUser()->getClient(), $form->get('label')->getData(), $form->get('start')->getData(), $form->get('end')->getData()));
        $pager = new Pagerfanta($adapter);

        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('pages/objective/index.html.twig', [
            'form' => $form->createView(),
            'pager' => $pager
        ]);
    }


    #[Route('/creer', name: 'app_main_objective_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ObjectiveRepository $objectiveRepository): Response
    {
        $objective = new Objective();
        $form = $this->createForm(ObjectiveType::class, $objective);
        $client = $this->getUser()->getClient();
        $objective->setClient($client);

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

    #[Route('/modifier/{id}', name: 'app_main_objective_edit', methods: ['GET', 'POST'])]
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

    #[Route('/supprimer/{id}', name: 'app_main_objective_delete', methods: ['GET'])]
    public function delete(Request $request, Objective $objective, ObjectiveRepository $objectiveRepository): Response
    {
        $this->denyAccessUnlessGranted(ObjectiveVoter::ACCESS, $objective);

        $objectiveRepository->remove($objective, true);
        $this->addFlash('success', 'Votre objectif à était supprimé');

        return $this->redirectToRoute('app_main_objective_index', [], Response::HTTP_SEE_OTHER);
    }
}
