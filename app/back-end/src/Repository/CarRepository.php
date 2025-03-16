<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function report()
    {
        $entityManager = $this->getEntityManager();
        $con = $entityManager->getConnection();
        $sql = "SELECT C.car_vin, C.car_make, C.car_model, COUNT(B.bkg_id), SUM(B.bkg_cost)
                FROM car C INNER JOIN booking B ON B.car_vin = C.car_vin
                GROUP BY C.car_vin";
        $stmt = $con->prepare($sql);
        $result = $stmt->executeQuery();
        $cars = $result->fetchAllAssociative();
        return $cars;
    }

    public function getCarsWithPagination(int $page, int $itemsPerPage): mixed {
        $queryBuilder = $this->createQueryBuilder('c')
            ->orderBy('c.car_vin', 'DESC');
        $queryBuilder->setFirstResult(($page - 1) * $itemsPerPage)->setMaxResults($itemsPerPage);
        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return Car[] Returns an array of Car objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Car
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
