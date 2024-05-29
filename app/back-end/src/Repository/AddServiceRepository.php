<?php

namespace App\Repository;

use App\Entity\AddService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AddService>
 *
 * @method AddService|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddService|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddService[]    findAll()
 * @method AddService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddService::class);
    }

//    /**
//     * @return AddService[] Returns an array of AddService objects
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

//    public function findOneBySomeField($value): ?AddService
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
