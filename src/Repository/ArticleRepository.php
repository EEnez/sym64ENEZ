<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findBySection(Section $section): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.sections', 's')
            ->where('s.id = :sectionId')
            ->andWhere('a.publishedAt IS NOT NULL')
            ->setParameter('sectionId', $section->getId())
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLatestPublished(int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.publishedAt IS NOT NULL')
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPublishedPaginated(int $page = 1, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.publishedAt IS NOT NULL')
            ->orderBy('a.publishedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return [
            'articles' => $qb->getQuery()->getResult(),
            'totalItems' => $this->count(['publishedAt' => ['$ne' => null]])
        ];
    }

    public function findRelatedArticles(Article $article, int $limit = 3): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.sections', 's')
            ->where('a.id != :articleId')
            ->andWhere('a.publishedAt IS NOT NULL')
            ->andWhere('s.id IN (:sectionIds)')
            ->setParameter('articleId', $article->getId())
            ->setParameter('sectionIds', $article->getSections()->map(fn($s) => $s->getId())->toArray())
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
