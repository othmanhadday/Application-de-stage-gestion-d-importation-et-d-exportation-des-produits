<?php

namespace App\Repository;

use App\Entity\FicheArrivageMagasinier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheArrivageMagasinier|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheArrivageMagasinier|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheArrivageMagasinier[]    findAll()
 * @method FicheArrivageMagasinier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheArrivageMagasinierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheArrivageMagasinier::class);
    }

    // /**
    //  * @return FicheArrivageMagasinier[] Returns an array of FicheArrivageMagasinier objects
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
    public function findOneBySomeField($value): ?FicheArrivageMagasinier
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
