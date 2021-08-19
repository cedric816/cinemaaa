<?php

namespace App\Repository;

use App\Entity\StartQuantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StartQuantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method StartQuantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method StartQuantity[]    findAll()
 * @method StartQuantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StartQuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StartQuantity::class);
    }

    // /**
    //  * @return StartQuantity[] Returns an array of StartQuantity objects
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
    public function findOneBySomeField($value): ?StartQuantity
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
