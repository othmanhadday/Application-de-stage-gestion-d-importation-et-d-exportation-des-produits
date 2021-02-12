<?php

namespace App\Repository;

use App\Entity\ArticleFicheArrivageAchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticleFicheArrivageAchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleFicheArrivageAchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleFicheArrivageAchat[]    findAll()
 * @method ArticleFicheArrivageAchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleFicheArrivageAchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleFicheArrivageAchat::class);
    }

    // /**
    //  * @return ArticleFicheArrivageAchat[] Returns an array of ArticleFicheArrivageAchat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleFicheArrivageAchat
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
