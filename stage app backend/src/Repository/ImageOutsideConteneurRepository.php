<?php

namespace App\Repository;

use App\Entity\ImageOutsideConteneur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageOutsideConteneur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageOutsideConteneur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageOutsideConteneur[]    findAll()
 * @method ImageOutsideConteneur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageOutsideConteneurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageOutsideConteneur::class);
    }

    // /**
    //  * @return ImageOutsideConteneur[] Returns an array of ImageOutsideConteneur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageOutsideConteneur
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
