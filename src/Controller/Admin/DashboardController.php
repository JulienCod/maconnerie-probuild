<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(
        EntityManagerInterface $entityManager,
    ): Response
    {
        // mettre en place un récapitulatif des données
        $countContact = count($entityManager->getRepository(Contact::class)->findAll());
        $countArticle = count($entityManager->getRepository(Articles::class)->findAll());
        return $this->render('admin/dashboard/index.html.twig',compact('countContact','countArticle'));
    }
}
