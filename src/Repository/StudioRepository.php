<?php

namespace App\Repository;

use App\Entity\Studio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Studio>
 *
 * @method Studio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studio[]    findAll()
 * @method Studio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Studio::class);
    }

    public function add(Studio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Studio $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Studio[] Returns an array of Studio objects
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

//    public function findOneBySomeField($value): ?Studio
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getCount(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getStudiosBySubscriber(int $userId): array
    {
        return $this->createQueryBuilder('n')
            ->innerJoin('n.subscriber', 's', 'WITH', 's.id = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getStudiosAndGames(): array
    {
        return $this->createQueryBuilder('n')
            ->select('n.name, n.description, n.date, g.name, g.genre, g.releaseDate, g.announceDate, g.description')
            ->innerJoin('n.games', 'g', 'WITH')
            ->getQuery()
            ->getArrayResult()
            ;
    }

}
