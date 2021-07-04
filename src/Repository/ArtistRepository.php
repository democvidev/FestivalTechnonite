<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    /**
     * Recherche les artistes en fonction du slug de la catégorie
     * @return Artist[] Returns an array of Artist objects
     */
    public function findByCategorySlug(string $categorySlug = null): array
    {
        $query = $this->createQueryBuilder('a'); // SELECT * FROM artist
        // ->select('a.id', 'a.name' , 'a.isLive', 'a.description', 'a.concert'); 
        if ($categorySlug != null) {
            $query->innerJoin('a.category', 'c');
            $query->andWhere('c.slug = :slug')->setParameter('slug', $categorySlug);
        }
        return $query->getQuery()->getResult();
    }


    /**
     * Renvoie un tableau avec un nombre d'artistes
     *
     * @param integer $nb
     * @return void
     */
    public function findByConcert(int $nb = 1): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT a.id, a.name, a.concert, a.description, a.slug 
            FROM App\Entity\Artist a 
            WHERE a.concert 
            IS NOT NULL
            GROUP BY a.concert'
        )->setMaxResults($nb);
        return $query->getResult();
    }


    /**
     * Renvoie la liste des artistes en concert
     * @return Artist[] Returns an array of Artist objects
     */
    public function findArtitsInConcert()
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.concert IS NOT NULL');
        return $query->getQuery()->getResult();
    }

    // /**
    //  * Recherche les artistes en fonction de la catégorie
    //  * @return Artist[] Returns an array of Artist objects
    //  */    
    // public function findByCategory(int $category = null)
    // {
    //     $query = $this->createQueryBuilder('a'); // SELECT * FROM artist
    //         // ->select('a.id', 'a.name' , 'a.isLive', 'a.description', 'a.concert'); 
    //         if($category != null) {
    //             $query->innerJoin('a.category', 'c');
    //             $query->andWhere('c.id = :id')->setParameter('id', $category);
    //         } 
    //     return $query->getQuery()->getResult();
    // }




    // /**
    //  * @return Artist[] Returns an array of Artist objects
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
    public function findOneBySomeField($value): ?Artist
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
