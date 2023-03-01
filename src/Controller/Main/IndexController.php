<?php

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/dashboard', name: 'index', options: ['sitemap' => true])]
    public function index(): Response
    {
        return $this->render('pages/dashboard.html.twig');
    }
}
