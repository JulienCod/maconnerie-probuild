<?php

use PHPUnit\Framework\TestCase;
use App\Service\BruteForceService;
use App\Entity\BruteForceAttempt;
use App\Repository\BruteForceAttemptRepository;
use Doctrine\ORM\EntityManagerInterface;

class BruteForceServiceTest extends TestCase
{
    public function testLogAttempt()
    {
        // Créer des doubles de remplacement pour les dépendances
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(BruteForceAttemptRepository::class);

        // Configurer le comportement du repository simulé
        $repository->expects($this->once())
            ->method('findByIp')
            ->willReturn(null); // Pour simuler aucune tentative existante

        $entityManager->expects($this->once())
            ->method('persist');

        $entityManager->expects($this->once())
            ->method('flush');

        // Créer une instance du service en injectant les doubles de remplacement
        $bruteForceService = new BruteForceService($entityManager, $repository);

        // Appeler la méthode à tester
        $ip = '127.0.0.1';
        $attempts = 1;
        $result = $bruteForceService->logAttempt($ip, $attempts);

        // Assurer que la méthode renvoie une instance de BruteForceAttempt
        $this->assertInstanceOf(BruteForceAttempt::class, $result);
    }
}
