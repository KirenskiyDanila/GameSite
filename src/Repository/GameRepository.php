<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function getReleasedGames(): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.releaseDate < CURRENT_DATE()')
            ->orderBy('n.releaseDate', 'DESC')
            ->getQuery()
            ->setMaxResults(5)
            ->getResult()
            ;
    }

    public function getGames(int $page): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.releaseDate', 'DESC')
            ->getQuery()
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10)
            ->getResult()
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

    public function getGamesBySearch(string $name): array
    {
        return $this->createQueryBuilder('n')
            ->where("n.name LIKE '%" . $name . "%'")
            ->orderBy('n.releaseDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function getGamesBySubscribtion(int $userId): array
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.studio', 's', 'WITH')
            ->innerJoin('s.subscriber', 'n', 'WITH', 'n.id = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getReleasedCountByMonth(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where("n.releaseDate > :date")
            ->setParameter('date', new \DateTime('-30 days'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getAnnouncedCountByMonth(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.announceDate > :date')
            ->setParameter('date', new \DateTime('-30 days'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getReleasedCountByYear(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where("n.releaseDate > :date")
            ->setParameter('date', new \DateTime('-365 days'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getAnnouncedCountByYear(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.announceDate > :date')
            ->setParameter('date', new \DateTime('-365 days'))
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getTotalSum(): array
    {
        return $this->createQueryBuilder('n')
            ->select('sum(p.cost)')
            ->innerJoin('n.prices', 'p', 'WITH')
            ->groupBy('n.id')
            ->getQuery()
            ->getArrayResult()
            ;
    }
}
