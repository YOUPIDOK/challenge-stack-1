<?php

namespace App\Controller\Main;

use App\Entity\Activity;
use App\Form\Activity\ActivityType;
use App\Form\Activity\SearchActivityType;
use App\Repository\ActivityRepository;
use App\Security\Main\Voter\ActivityVoter;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activites')]
#[IsGranted('ROLE_CLIENT')]
class ActivityController extends AbstractController
{
    #[Route('/', name: 'activities', methods: ['GET'])]
    public function activities(ActivityRepository $activityRepo, Request $request): Response
    {
        $form = $this->createForm(SearchActivityType::class);
        $form->handleRequest($request);

        $adapter = new QueryAdapter($activityRepo->searchActivityQb($this->getUser()->getClient(), $form->get('label')->getData()));
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->renderForm('pages/activity/activities.html.twig', [
            'form' => $form,
            'pager' => $pager
        ]);
    }

    #[Route('/creer', name: 'activity_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $activity = (new Activity())->setClient($this->getUser()->getClient());
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($activity);
            $em->flush();

            $this->addFlash('success', "L'activité a bien été ajoutée.");

            return $this->redirectToRoute('activities');
        }

        return $this->renderForm('pages/activity/create.html.twig', [
            'activity' => $activity,
            'form' => $form
        ]);
    }

    #[Route('/modifier/{id}', name: 'activity_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Activity $activity, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(ActivityVoter::ACCESS, $activity);

        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'L\'activité a bien été modifiée.');

            return $this->redirectToRoute('activities');
        }

        return $this->renderForm('pages/activity/update.html.twig', [
            'activity' => $activity,
            'form' => $form
        ]);
    }

    #[Route('/supprimer/{id}', name: 'activity_delete')]
    public function delete(Activity $activity, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(ActivityVoter::ACCESS, $activity);

        $em->remove($activity);
        $em->flush();

        $this->addFlash('success', "L'activité a bien été supprimée.");

        return $this->redirectToRoute('foods',);
    }
}
