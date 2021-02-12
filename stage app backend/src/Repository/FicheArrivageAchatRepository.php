<?php

namespace App\Repository;

use App\Entity\FicheArrivageAchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheArrivageAchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheArrivageAchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheArrivageAchat[]    findAll()
 * @method FicheArrivageAchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheArrivageAchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheArrivageAchat::class);
    }

    // /**
    //  * @return FicheArrivageAchat[] Returns an array of FicheArrivageAchat objects
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
    public function findOneBySomeField($value): ?FicheArrivageAchat
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findFicheArrvageAll($value)
    {
        return $this
            ->createQueryBuilder('f')
            ->join('f.$articleFicheArrivageAchats = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }


}
