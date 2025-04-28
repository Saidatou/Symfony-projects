<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\ORM\Query;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function save(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createAllPublishedOrderedByNewestQuery(?Tag $tag): Query
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->addSelect('t')
            ->leftJoin('p.tags', 't')
            // ->andWhere('p.publishedAt <= :now')
            ->orderBy('p.publishedAt', 'DESC');
            // ->setParameter('now', new DateTimeImmutable);

        if ($tag) {
            $queryBuilder->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        return $queryBuilder->getQuery();;
    }

    /**
     * @return Post[] Returns an array of Post objects similar with the given post
     */
    public function findSimilar(Post $post, int $maxResults = 4): array
    {
        // récupérer les articles
        // ayant des tags en commun
        // avec l'article passé en argument ✅
        // ordonnés de l'article ayant le plus de tags en commun
        // à l'article ayant le moins de tags en commun.

        // Dans le cas où des articles ont le même nombre de tags
        // en commun avec $post, alors ils devront être ordonnés du plus récent
        // au plus ancien. ✅

        // On retournera au maximum 4 articles. ✅
        // PS: Pourquoi pas la valeur "4" devra être customisable. ✅
        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->addSelect('COUNT(t.id) AS HIDDEN numberOfTags')
            // ->addSelect('COUNT(t.id) AS HIDDEN numberOfTags')
            ->andWhere('t IN (:tags)')
            ->andWhere('p != :post')
            ->setParameters([
                'tags' => $post->getTags(),
                'post' => $post,
            ])
            ->groupBy('p.id')
            ->addorderBy('numberOfTags' , 'DESC')
            ->addorderBy('p.publishedAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findMostCommented(int $maxResults)
    {
        return $this->createQueryBuilder('p')
            ->join('p.comments', 'c')
            ->addSelect('COUNT(c) AS HIDDEN numberOfComments')
            ->andWhere('c.isActive = true')
            ->groupBy('p')
            ->orderBy('numberOfComments' , 'DESC')
            ->addorderBy('p.publishedAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findOneByPublishDateAndSlug(string $date, string $slug): ?Post
    {
        return $this->createQueryBuilder('p')
            // ->andwhere('p.publishedAt IS NOT NULL')
            ->andWhere('DATE(p.publishedAt) = :date')
            ->andWhere('p.slug = :slug')
            ->setParameters([

                'date' => $date,
                'slug' => $slug
            ])
            
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function search(string $searchTerm): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('LOWER(p.title) LIKE :searchTerm OR LOWER(p.body) LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.mb_strtolower($searchTerm).'%')
            ->getQuery()
            ->getResult()
        ;
    }

    

    // public function findOneByPublishDateAndSlug(int $year, int $month, int $day, string $slug):?Post
    // {
    //     return $this->createQueryBuilder('p')
    //                ->andWhere('YEAR(p.publishedAt) = :year')
    //                ->andWhere('MONTH(p.publishedAt) = :month')
    //                ->andWhere('DAY(p.publishedAt) = :day')
    //                ->andWhere('p.slug = :slug')
    //                ->setParameters([
    //                 'year'=> $year,
    //                 'month'=> $month,
    //                 'day'=> $day,
    //                 'slug'=> $slug
    //                 ])
    //                 // ->setParameters(compact('year', 'month', 'day', 'slug'))
    //                ->getQuery()
    //                ->getOneOrNullResult()
    //            ;

    // }




    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
