<?php

namespace App\Repository;

use App\Entity\Dume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dume[]    findAll()
 * @method Dume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dume::class);
    }

    // /**
    //  * @return Dume[] Returns an array of Dume objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dume
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
