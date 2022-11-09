<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function add(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Review[] Returns an array of Review objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Review
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getReviewsByGameId(int $id, int $page): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.game = :id' )
            ->setParameter('id', $id)
            ->andWhere('n.approved = true')
            ->orderBy('n.date', 'DESC')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getReviewsByUserId(int $id, int $page): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.author = :id' )
            ->setParameter('id', $id)
            ->orderBy('n.date', 'DESC')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getLastReviewsById(int $id): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.game = :id' )
            ->setParameter('id', $id)
            ->andWhere('n.approved = true')
            ->orderBy('n.date', 'DESC')
            ->getQuery()
            ->setMaxResults(5)
            ->getResult()
            ;
    }

    public function getCountById(int $id): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.game = :id' )
            ->setParameter('id', $id)
            ->andWhere('n.approved = true')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getApprovedCountByMonth(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-30 days'))
            ->andWhere('n.approved = true')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getDisapprovedCountByMonth(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-30 days'))
            ->andWhere('n.approved = false')
            ->andWhere('n.admin is not null')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getUncheckedCountByMonth(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-30 days'))
            ->andWhere('n.approved = false')
            ->andWhere('n.admin is null')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getCountByMonth(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-30 days'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getApprovedCountByYear(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-365 days'))
            ->andWhere('n.approved = true')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getDisapprovedCountByYear(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-365 days'))
            ->andWhere('n.approved = false')
            ->andWhere('n.admin is not null')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getUncheckedCountByYear(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-365 days'))
            ->andWhere('n.approved = false')
            ->andWhere('n.admin is null')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getCountByYear(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.date > :date')
            ->setParameter('date', new \DateTime('-365 days'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getApprovedCount(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.approved = true')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getDisapprovedCount(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.approved = false')
            ->andWhere('n.admin is not null')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getUncheckedCount(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.approved = false')
            ->andWhere('n.admin is null')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getCount(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getReviewsAndAuthors(): array
    {
        return $this->createQueryBuilder('n')
            ->select('n.text, n.date, n.approved, n.grade, u.username, u.firstname, u.lastname, u.email, u.date, u.roles')
            ->innerJoin('n.author', 'u', 'WITH')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
