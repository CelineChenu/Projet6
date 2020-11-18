<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findOld($user)
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('e')
            ->innerJoin('e.users','u')
            ->where('u.id = :userId')
            ->andWhere('e.date < :date')
            ->setParameter(':date',$date)
            ->setParameter(':userId',$user->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFuture($user)
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('e')
            ->innerJoin('e.users','u')
            ->where('u.id = :userId')
            ->andWhere('e.date >= :date')
            ->setParameter(':date',$date)
            ->setParameter(':userId',$user->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLastFive()
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('e')
            ->where('e.date >= :date')
            ->setParameter(':date',$date)
            ->orderBy('e.date','DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
