<?php

namespace App\Repository;

use App\Entity\BruteForceAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BruteForceAttempt>
 *
 * @method BruteForceAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method BruteForceAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method BruteForceAttempt[]    findAll()
 * @method BruteForceAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BruteForceAttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BruteForceAttempt::class);
    }

    public function findByIp(string $ip): ?BruteForceAttempt
    {
        return $this->findOneBy(['ip' => $ip]);
    }

    // Nouvelle fonction de recherche par IP et renvoi de isBan
    public function findIsBanByIp(string $ip): ?bool
    {
        $result = $this->createQueryBuilder('bfa')
            ->select('bfa.isBan')
            ->where('bfa.ip = :ip')
            ->setParameter('ip', $ip)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['isBan'] : null;
    }

//    /**
//     * @return BruteForceAttempt[] Returns an array of BruteForceAttempt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BruteForceAttempt
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
