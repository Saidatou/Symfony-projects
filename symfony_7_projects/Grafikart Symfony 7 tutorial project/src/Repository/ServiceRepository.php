<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
// use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
// use  Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
  
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Service::class);
    }

   

    // public function paginateServices(int $page, int $limit)
    // public function paginateServices(int $page )
    public function paginateServices(int $page, ?int $userId )
    {
        $builder =  $this->createQueryBuilder('s')->leftJoin('s.category','c')->select('s','c');
        if($userId){
           $builder =  $builder->andWhere('s.user = :user')
                        ->setParameter('user', $userId);
        }
        return $this->paginator->paginate(
            // $this->createQueryBuilder('s')->leftJoin('s.category','c')->select('s','c'),
            $builder,
            $page,
            20,
            [
                'distinct'=>true,
                'sortFieldAllowedList'=>['s.id', 's.title']
            ]
        );

        /*
        return new Paginator($this
        ->createQueryBuilder('r')
        ->setFirstResult(($page -1) * $limit)
        ->setMaxResults($limit)
        ->getQuery()
        ->setHint(Paginator::HINT_ENABLE_DISTINCT, false), false
        );
        */
    }

    // avoir le total de la durée des requêtes

    public function findTotalDuration()
    {
        return $this->createQueryBuilder('s')
        ->select('SUM(s.duration) as total')
        ->getQuery()
        ->getResult();
    }

    // s'il y a une propriété duration qui peut être null
    /**
     * @return Service[]
     */
    
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('s')
        ->select('s','c')
        ->where('s.duration >= :duration')
        ->orderBy('s.duration','ASC')
        ->leftJoin('s.category','c')
        ->setMaxResults(10)
        ->setParameter('duration', $duration)
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
