<?php

namespace App\Repository;

use App\Entity\FicheArrivageFinance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheArrivageFinance|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheArrivageFinance|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheArrivageFinance[]    findAll()
 * @method FicheArrivageFinance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheArrivageFinanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheArrivageFinance::class);
    }

    // /**
    //  * @return FicheArrivageFinance[] Returns an array of FicheArrivageFinance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FicheArrivageFinance
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
