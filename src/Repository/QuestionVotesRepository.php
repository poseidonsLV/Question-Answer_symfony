<?php

namespace App\Repository;

use App\Entity\QuestionVotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method QuestionVotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionVotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionVotes[]    findAll()
 * @method QuestionVotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionVotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionVotes::class);
    }

    // /**
    //  * @return QuestionVotes[] Returns an array of QuestionVotes objects
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
    public function findOneBySomeField($value): ?QuestionVotes
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
