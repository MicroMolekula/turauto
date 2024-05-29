<?php

namespace App\Repository;

use App\Entity\StationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StationService>
 *
 * @method StationService|null find($id, $lockMode = null, $lockVersion = null)
 * @method StationService|null findOneBy(array $criteria, array $orderBy = null)
 * @method StationService[]    findAll()
 * @method StationService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StationService::class);
    }

//    /**
//     * @return StationService[] Returns an array of StationService objects
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

//    public function findOneBySomeField($value): ?StationService
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
