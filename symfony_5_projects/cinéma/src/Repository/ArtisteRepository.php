<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\Artiste;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Artiste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artiste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artiste[]    findAll()
 * @method Artiste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artiste::class);
    }

    public function findByFilm(Film $film){
        $qb=$this->createQueryBuilder("a")
        ->innerJoin("a.filmRealises","fr")
        ->innerJoin("a.filmsJoues", "fj")
        ->andWhere("fr.id = :film")
        ->orWhere("fj.id = :film")
        ->setParameters([
            "film"=>$film->getId()
        ]);
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Artiste[] Returns an array of Artiste objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Artiste
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
