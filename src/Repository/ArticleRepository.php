<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


//Filtrage des articles
    /**
     * @param array $criteria
     * @return Article[]
     */
    public function findByCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('a');

        if (!empty($criteria['name'])) {
            $qb->andWhere('a.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        if (!empty($criteria['category'])) {
            $qb->andWhere('a.category = :category')
                ->setParameter('category', $criteria['category']);
        }
        if (!empty($criteria['user'])) {
            $qb->andWhere('a.user = :username')
                ->setParameter('username', $criteria['user']);
        }

        return $qb->getQuery()->getResult();
    }
}
