<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FuturProjectsController extends AbstractController
{
    #[Route('/projets-futurs', name: 'app_futur_projects')]
    public function index(): Response
    {
        return $this->render('futur_projects/index.html.twig');
    }
}
