<?php

namespace App\Repository\Api;

use App\Entity\Api\ReportController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportController|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportController|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportController[]    findAll()
 * @method ReportController[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportControllerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportController::class);
    }

    // /**
    //  * @return ReportController[] Returns an array of ReportController objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportController
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
