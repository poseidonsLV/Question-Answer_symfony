<?php

namespace App\Repository;

use App\Entity\QuestionViews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionViews|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionViews|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionViews[]    findAll()
 * @method QuestionViews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionViewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionViews::class);
    }

    // /**
    //  * @return QuestionViews[] Returns an array of QuestionViews objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionViews
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
