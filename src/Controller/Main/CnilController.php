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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CnilController extends AbstractController
{
    #[Route('/conditions-generales', name: 'conditions_generales', methods: ['GET'])]
    public function conditions_generales()
    {
        return $this->render('pages/cnil/conditions_generales.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions_legales', methods: ['GET'])]
    public function mentions_legales()
    {
        return $this->render('pages/cnil/mentions_legales.html.twig');
    }

    #[Route('/politiques-de-confidentialite', name: 'politiques_de_confidentialite', methods: ['GET'])]
    public function politiques_de_confidentialite()
    {
        return $this->render('pages/cnil/politiques_de_confidentialite.html.twig');
    }

}
