<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\FilmSearch;
use App\Entity\Sorting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function findAllQuery($key, FilmSearch $search)
    {
        if ($search->getKeyWord()){
            return $this->createQueryBuilder('f')
            ->andWhere('f.available = :val')
            ->andWhere('f.quantity > 0')
            ->andWhere('f.title LIKE :key OR f.plot LIKE :key')
            ->setParameter('val', true)
            ->setParameter('key', '%'.$search->getKeyWord().'%')
            ->orderBy('f.'.$key, 'ASC')
            ->getQuery();
        } else {
            return $this->createQueryBuilder('f')
            ->andWhere('f.available = :val')
            ->andWhere('f.quantity > 0')
            ->setParameter('val', true)
            ->orderBy('f.'.$key, 'ASC')
            ->getQuery();
        }   
    }

    public function findLatest()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Film[] Returns an array of Film objects
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
    public function findOneBySomeField($value): ?Film
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
