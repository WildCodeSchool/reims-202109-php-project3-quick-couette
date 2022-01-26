<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
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

    /**
     * @return Order[] Returns an array of Order objects
     */
    public function findLikeNameOrReference(string $search, int $offset, int $limit): array
    {
        /** @var Order[] */
        $orders = $this->createQueryBuilder('o')
            ->orWhere('o.name LIKE :search')
            ->orWhere('o.reference LIKE :search')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->addOrderBy('o.savedAt', 'DESC')
            ->addOrderBy('o.id', 'DESC')
            ->setParameter('search', "%$search%")
            ->getQuery()
            ->getResult()
        ;
        return $orders;
    }

    public function findLikeNameOrReferenceCount(string $search): int
    {
        /** @var int */
        $count = $this->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->orWhere('o.name LIKE :search')
            ->orWhere('o.reference LIKE :search')
            ->setParameter('search', "%$search%")
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $count;
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
