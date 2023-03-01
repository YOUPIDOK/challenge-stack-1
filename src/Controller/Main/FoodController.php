<?php

namespace App\Controller\Main;

use App\Entity\Food;
use App\Form\Food\FoodType;
use App\Form\Food\SearchFoodType;
use App\Repository\FoodRepository;
use App\Security\Main\Voter\FoodVoter;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('nourritures')]
#[IsGranted('ROLE_CLIENT')]
class FoodController extends AbstractController
{
    #[Route('/', name: 'foods', methods: ['GET'])]
    public function foods(FoodRepository $foodRepo, Request $request): Response
    {
        $form = $this->createForm(SearchFoodType::class);
        $form->handleRequest($request);

        $adapter = new QueryAdapter($foodRepo->searchFoodQb($this->getUser()->getClient(), $form->get('label')->getData()));
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(2);
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('pages/food/foods.html.twig', [
            'form' => $form->createView(),
            'pager' => $pager
        ]);
    }

    #[Route('/creer', name: 'food_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $food = (new Food())->setClient($this->getUser()->getClient());
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($food);
            $em->flush();

            $this->addFlash('success', 'La nourriture a bien été ajoutée.');

            return $this->redirectToRoute('foods');
        }

        return $this->renderForm('pages/food/create.html.twig', [
            'food' => $food,
            'form' => $form
        ]);
    }

    #[Route('/modifier/{id}', name: 'food_update', methods: ['GET', 'POST'])]
    public function update(Request $request, Food $food, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(FoodVoter::ACCESS, $food);

        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La nourriture a bien été modifiée.');
        }

        return $this->renderForm('pages/food/update.html.twig', [
            'food' => $food,
            'form' => $form
        ]);
    }

    #[Route('/supprimer/{id}', name: 'food_delete')]
    public function delete(Food $food, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(FoodVoter::ACCESS, $food);

        $em->remove($food);
        $em->flush();

        $this->addFlash('success', 'La nourriture a bien été supprimée.');

        return $this->redirectToRoute('foods',);
    }
}
