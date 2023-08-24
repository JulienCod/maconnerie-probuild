<?php

namespace App\Controller;

use App\Entity\BruteForceAttempt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/brute/force/attempt', name: 'admin_brute_force_attempt_')]
class BruteForceAttemptController extends AbstractController
{
    public function __construct()
    {
        
    }
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // $bruteForceAttempt = new BruteForceAttempt();
        // $bruteForceAttempt->setIp($attackerIp);
        // $bruteForceAttempt->setAttempts($numberOfAttempts);
        // $bruteForceAttempt->setIsBan(false);

        // $entityManager->persist($bruteForceAttempt);
        // $entityManager->flush();
        return $this->render('brute_force_attempt/index.html.twig');
    }
}
