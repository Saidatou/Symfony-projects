<?php

namespace App\Repository;

use App\Entity\Gogo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gogo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gogo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gogo[]    findAll()
 * @method Gogo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GogoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gogo::class);
    }

    // /**
    //  * @return Gogo[] Returns an array of Gogo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gogo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
