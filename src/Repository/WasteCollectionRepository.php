<?php

namespace App\Repository;

use App\Entity\WasteCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WasteCollection>
 */
class WasteCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WasteCollection::class);
    }

    //    /**
    //     * @return WasteCollection[] Returns an array of WasteCollection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WasteCollection
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllWithItems(): array
    {
    return $this->createQueryBuilder('wc')
        ->select('DISTINCT wc, u, l, wi, wt')
        ->leftJoin('wc.user', 'u')->addSelect('u')
        ->leftJoin('wc.location', 'l')->addSelect('l')
        ->leftJoin('wc.wasteItems', 'wi')->addSelect('wi')
        ->leftJoin('wi.wasteType', 'wt')->addSelect('wt')
        ->orderBy('wc.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
}

}
