<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function search($mots = null, $departement = null)
    {
        //Je vais initialiser le $query qui va prendre le query builderavec ‘a’ comme annonce. 
        //Ensuite on va faire un where parce qu’on veut les annonces actives(‘a.active=1’) ; 
        //et ensuite on va ajouter les mots mais on va rajouter les mots. 
        //Mais on va d’abord vérifié si le mot est différent de null avec la condition if($mot != null)
        $query = $this->createQueryBuilder('a');
        $query->where('a.active = 1');
        if ($mots != null) {
            //Etant donnée qu’on a fait :mots , c’est un paramètre on doit rajouter un set parametre ou on va chercher mot 
            //et on met la variable $mot. Et derniere chose on fait on fait un return de $query ->get result ;
            $query->andWhere('MATCH_AGAINST(a.title, a.nickname, a.description, a.experience, a.city) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if ($departement != null) {
            //Je fais une jonture directement de ma table departement par la propriété département de ma table annonce
            $query->leftJoin('a.departement', 'd');
            //je vais vérifier que l'id du département est bien celui qui a été envoyé par ma function search
            $query->andWhere('d.id = :id')
                ->setParameter('id', $departement);
        }

        // if ($places != null) {
        //     $query->leftJoin('a.places', 'p');
        //     $query->andWhere('p.id = :id')
        //         ->setParameter('id', $places);
        // }
        return $query->getQuery()->getResult();
    }



    /**
     * @return Annonce[] Returns an array of Annonce objects
     */

    public function findWithSearch($search)
    {
        $query = $this->createQueryBuilder('a');



        //condition sur la ville. On regarde si c'est définit en faisant un if et ajouter une condition
        if ($search->getCity()) {
            $query = $query->andWhere('a.city like:val')
                ->setParameter('val', "%{$search->getCity()}%");
        }
        // condition sur les tableaux
        //D'abord la région
        if ($search->getRegion()) {
            //s'il y a des éléments dans le tableau, nous allons rajouté une catégories
            //comme la region n'est pas une propriété de l'enité annonce, nous allon faire une jointure
            $query = $query->join('a.region', 'r')
                //nous allons mettre les critères sur lesquelles nous souhaitons faire la jointure 
                //nous voulons que le r.id qui est l'identifiant de la région soit parmis les régions que
                //nous avons récupérés ici un ou plsieurs regions
                ->andWhere('r.id IN(:region)')
                ->setParameter('region', $search->getRegion());
        }
        //Ensuite le département
        if ($search->getDepartement()) {
            $query = $query->join('a.departement', 'd')
                //nous allons mettre les critères sur lesquelles nous souhaitons faire la jointure 
                //nous voulons que le r.id qui est l'identifiant du déparetment soit parmis les régions que
                //nous avons récupérés ici un ou plusieurs departement
                ->andWhere('d.id IN(:departement)')
                ->setParameter('departement', $search->getDepartement());
        }
        //Enfin les Places
        if ($search->getPlaces()) {
            //s'il y a des éléments dans le tableau, nous allons rajouté une catégories
            //comme la region n'est pas une propriété de l'enité annonce, nous allon faire une jointure
            $query = $query->join('a.place', 'p')
                //nous allons mettre les critères sur lesquelles nous souhaitons faire la jointure 
                //nous voulons que le r.id qui est l'identifiant de la places soit parmis les régions que
                //nous avons récupérés ici un ou plsieurs places
                ->andWhere('p.id IN(:places)')
                ->setParameter('places', $search->getPlaces());
        }
        dd($query->getQuery()->getResult());
    }



    // /**
    //  * Returns all Annonces par page
    //  * 
    //  */
    // public function getPaginatedAnnonces($page, $limit)
    // {
    //     $query = $this->createQueryBuilder('a')
    //         ->where('a.active = 1')
    //         ->orderBy('a.maj')
    //         ->setFirstResult(($page * $limit) - $limit)
    //         ->setMaxResults($limit);
    //     return $query->getQuery()->getResult();
    // }

    // /**
    //  * Returns number of annonces
    //  * 
    //  */
    // public function getTotalAnnonces()
    // {
    //     $query = $this->createQueryBuilder('a')
    //         ->select('COUNT(a)')
    //         ->where('a.active = 1');
    //     return $query->getQuery()->getSingleScalarResult();
    // }




    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
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
    public function findOneBySomeField($value): ?Annonce
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
