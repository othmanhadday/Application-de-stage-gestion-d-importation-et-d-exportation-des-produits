<?php

namespace App\Repository;

use App\Entity\Conteneur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conteneur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conteneur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conteneur[]    findAll()
 * @method Conteneur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConteneurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conteneur::class);
    }

    // /**
    //  * @return Conteneur[] Returns an array of Conteneur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conteneur
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
