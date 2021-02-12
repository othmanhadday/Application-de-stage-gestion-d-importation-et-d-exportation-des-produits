<?php

namespace App\Repository;

use App\Entity\FicheArrivageMagasinierManutentionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheArrivageMagasinierManutentionnaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheArrivageMagasinierManutentionnaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheArrivageMagasinierManutentionnaire[]    findAll()
 * @method FicheArrivageMagasinierManutentionnaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheArrivageMagasinierManutentionnaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheArrivageMagasinierManutentionnaire::class);
    }

    // /**
    //  * @return FicheArrivageMagasinierManutentionnaire[] Returns an array of FicheArrivageMagasinierManutentionnaire objects
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
    public function findOneBySomeField($value): ?FicheArrivageMagasinierManutentionnaire
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
