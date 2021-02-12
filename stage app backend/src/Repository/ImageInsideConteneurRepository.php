<?php

namespace App\Repository;

use App\Entity\ImageInsideConteneur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageInsideConteneur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageInsideConteneur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageInsideConteneur[]    findAll()
 * @method ImageInsideConteneur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageInsideConteneurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageInsideConteneur::class);
    }

    // /**
    //  * @return ImageInsideConteneur[] Returns an array of ImageInsideConteneur objects
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
    public function findOneBySomeField($value): ?ImageInsideConteneur
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
