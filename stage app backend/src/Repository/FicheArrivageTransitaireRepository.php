<?php

namespace App\Repository;

use App\Entity\FicheArrivageTransitaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheArrivageTransitaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheArrivageTransitaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheArrivageTransitaire[]    findAll()
 * @method FicheArrivageTransitaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheArrivageTransitaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheArrivageTransitaire::class);
    }

    // /**
    //  * @return FicheArrivageTransitaire[] Returns an array of FicheArrivageTransitaire objects
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
    public function findOneBySomeField($value): ?FicheArrivageTransitaire
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
