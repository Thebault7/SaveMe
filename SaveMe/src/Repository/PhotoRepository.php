<?php

namespace App\Repository;

use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    public function accueil()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function findPhotosByPage($page = 0, $limit = 8)
    {
        $entityManager = $this->getEntityManager();
        $dql = <<<DQL
SELECT p 
FROM App\Entity\Photo p
LEFT JOIN  p.category c
DQL;
        $query = $entityManager
            ->createQuery($dql)
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($query, true);

        return $paginator;

    }



    // /**
    //  * @return Photo[] Returns an array of Photo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Photo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
