<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Client;
use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function addNew(Client $client, Car $car, int $count_days)
    {
        $entityManager = $this->getEntityManager();
        $con = $entityManager->getConnection();
        $client_id = $client->getId();
        $car_vin = $car->getCarVin();
        $sql = 'CALL public.add_new_booking(:car_vin, :clt_id, :count_days)';
        $stmt = $con->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'car_vin' => $car_vin,
            'clt_id' => $client_id,
            'count_days' => $count_days,
        ]);
        return $car;
    }

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
