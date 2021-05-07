<?php

namespace App\Repository;

use App\Entity\Contributors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contributors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contributors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contributors[]    findAll()
 * @method Contributors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contributors::class);
    }

    // /**
    //  * @return Contributors[] Returns an array of Contributors objects
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
    public function findOneBySomeField($value): ?Contributors
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
