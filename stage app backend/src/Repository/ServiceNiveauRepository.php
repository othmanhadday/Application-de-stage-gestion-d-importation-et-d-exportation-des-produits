<?php

namespace App\Repository;

use App\Entity\ServiceNiveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceNiveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceNiveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceNiveau[]    findAll()
 * @method ServiceNiveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceNiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceNiveau::class);
    }

    // /**
    //  * @return ServiceNiveau[] Returns an array of ServiceNiveau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ServiceNiveau
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
