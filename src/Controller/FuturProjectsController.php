<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FuturProjectsController extends AbstractController
{
    #[Route('/nos-réalisations', name: 'app_portfolio')]
    public function index(): Response
    {
        return $this->render('pages/portfolio.html.twig');
    }
}
