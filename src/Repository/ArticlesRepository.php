<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Articles>
 *
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function findByCombinedCriteria(array $tags, array $categories)
{
    $qb = $this->createQueryBuilder('a'); // Création d'un constructeur de requêtes (QueryBuilder) pour l'entité Articles (alias "a")

    if (!empty($tags)) {
        $qb->join('a.tags', 't') // Joindre la relation ManyToMany "tags" de l'entité Articles avec l'alias "t"
           ->andWhere($qb->expr()->in('t.id', ':tags')) // Ajouter une condition pour vérifier si l'ID du tag est dans le tableau des IDs de tags sélectionnés
           ->setParameter('tags', $tags); // Définir les paramètres pour la condition
    }

    if (!empty($categories)) {
        $qb->join('a.categories', 'c') // Joindre la relation ManyToMany "categories" de l'entité Articles avec l'alias "c"
           ->andWhere($qb->expr()->in('c.id', ':categories')) // Ajouter une condition pour vérifier si l'ID de la catégorie est dans le tableau des IDs de catégories sélectionnées
           ->setParameter('categories', $categories); // Définir les paramètres pour la condition
    }
    return $qb->getQuery()->getResult(); // Exécuter la requête et obtenir les résultats
}


    //    /**
    //     * @return Articles[] Returns an array of Articles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Articles
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
