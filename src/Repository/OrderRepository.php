<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findOrders(
        ?string $search,
        ?DateTime $dateFrom,
        ?DateTime $dateTo,
        ?int $offset,
        ?int $limit
    ): array {
        $qb = $this->createQueryBuilder('o');

        if ($search) {
            $qb
                ->andWhere('o.name LIKE :search OR o.reference LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }
        if ($dateFrom) {
            $qb
                ->andWhere('o.savedAt >= :from')
                ->setParameter('from', $dateFrom)
            ;
        }
        if ($dateTo) {
            $qb
                ->andWhere('o.savedAt <= :to')
                ->setParameter('to', $dateTo)
            ;
        }

        $orders = $qb
            ->addOrderBy('o.savedAt', 'DESC')
            ->addOrderBy('o.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;

        $count = $qb
            ->resetDQLPart('orderBy')
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->select('COUNT(o)')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return [$orders, $count];
    }

    public function findByUser(User $user): array
    {
        /** @var Order[] */
        $orders = $this->createQueryBuilder('o')
            ->andWhere('o.user = :user')
            ->andWhere('o.status != :status')
            ->addOrderBy('o.savedAt', 'DESC')
            ->addOrderBy('o.id', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('status', Order::STATUS_NOT_A_COMMAND)
            ->getQuery()
            ->getResult()
        ;
        return $orders;
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
