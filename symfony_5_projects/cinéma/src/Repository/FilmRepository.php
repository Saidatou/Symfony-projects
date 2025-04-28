<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\Artiste;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    public function findByDateInterval(string $nom){
        $qb=$this->createQueryBuilder("f");
        $qb
        //->andWhere("f.titre = :titre")
        ->andWhere("f.dateDeSortie < :dateFin") 
        ->andWhere("f.dateDeSortie > :dateDebut") 
        ->setParameters([
        //"titre"=>"Ocean's Twelve",
        "dateDebut"=>new \Datetime(1998),
        "dateFin"=>new \Datetime("2005-01-01 00:00")
        ]);

        return $qb->getQuery()->getResult();
    }

    public function findByRealisateur(string $nom="Soderbergh"){
        $qb=$this->createQueryBuilder("f");
        $qb->innerJoin("f.realisateur","r")
            ->andWhere("r.nom = :nom")
            ->setParameters([
                "nom"=>$nom
            ]);
       

        return $qb->getQuery()->getResult();

    }

    public function searchByTitle(string $search){
        $qb=$this->createQueryBuilder("f")
        ->andWhere("f.titre LIKE :search")
        ->setParameters([
            "search"=>"%".$search."%"
        ]);
        return $qb->getQuery()->getResult();
    }

    public function findByActeur(Artiste $acteur){
        $qb=$this->createQueryBuilder("f");
        $qb->innerJoin("f.acteurs", "a")
        ->andWhere("a.id = :acteur")
        ->setParameters(["acteur"=>$acteur->getId()]);

        return $qb->getQuery()->getResult();
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
