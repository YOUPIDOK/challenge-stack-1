<?php

namespace App\Controller\Main;

use App\Repository\Data\WeightRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_CLIENT')]
#[Route('/mon-profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'profile')]
    public function profile(WeightRepository $weightRepo): Response
    {
        $weight = $weightRepo->findLastWeightByClient($this->getUser()->getClient());
        dd($weight);
        return $this->render('pages/profile/profile.html.twig');
    }

    #[Route('/modifier', name: 'profile_update')]
    public function update(): Response
    {
        return $this->render('pages/profile/profile_update.html.twig');
    }
}