<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BruteForceAttempt;
use App\Repository\BruteForceAttemptRepository;

class BruteForceService
{
    private $entityManager;
    private const MAX_ATTEMPTS_BEFORE_BAN = 3;
    private const ADD_ATTEMPTS = 1;
    private $bruteForceAttemptRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        BruteForceAttemptRepository $bruteForceAttemptRepository
    ) {
        $this->entityManager = $entityManager;
        $this->bruteForceAttemptRepository = $bruteForceAttemptRepository;
    }

    public function logAttempt($ip)
    {
        // recherche si l'adress ip est déjà enregistré en base de données
        $bruteForceAttempt = $this->bruteForceAttemptRepository->findByIp($ip);
        // si une ip est trouvée
        if ($bruteForceAttempt !== null) {
            // récupération du nombre de tentative en base de données + la valeur de attemps
            $countAttempts = $bruteForceAttempt->getAttempts() + self::ADD_ATTEMPTS;
            // si l'adresse ip à déjà fait 3 tentatives ou plus l'ip sera banni et il ne pourra plus avoir accès au envoie des formulaires du site
            if ($countAttempts >= self::MAX_ATTEMPTS_BEFORE_BAN) {
                $bruteForceAttempt->setAttempts($countAttempts);
                $bruteForceAttempt->setIsBan(true);
            }
            // sinon on ajout la nouvelle valeur de attemps à l'entitée
            else {
                $bruteForceAttempt->setAttempts($countAttempts);
            }
            $this->entityManager->persist($bruteForceAttempt);
        } else {
            $bruteForceAttempt = new BruteForceAttempt();
            $bruteForceAttempt->setIp($ip);
            $bruteForceAttempt->setAttempts(self::ADD_ATTEMPTS);
            $bruteForceAttempt->setIsBan(false);
            $this->entityManager->persist($bruteForceAttempt);
        }

        $this->entityManager->flush();
        return $bruteForceAttempt;
    }

    public function controlIsBan($ip)
    {
        $isBan = $this->bruteForceAttemptRepository->findIsBanByIp($ip);
        if ($isBan !== null) {
            return $isBan;
        } else {
            return false;
        }
    }
}
