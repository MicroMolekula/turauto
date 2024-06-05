<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Client;
use App\Entity\Manager;
use App\Entity\StationService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Car;

class BookingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $bookingArray = [
            ['41947609732412329', 'zaiceva1996@gmail.com', '6404561972', 'пл. Мира, 2', '2023-11-01 11:32:00', '2023-11-04 12:33:00', 8000, 2],
            ['46998607911885010', 'baranov1999@gmail.com', '5574104392', 'ул. Неделина, 2В', '2023-11-02 13:29:00', '2023-11-05 11:14:00', 14500, 2],
            ['16623052788306146', 'nikulin1990@gmail.com', '5574104392', 'ул. Неделина, 2В', '2023-11-05 14:24:00', '2023-11-06 10:19:00', 10500, 2],
            ['93876883250772029', 'polyakova1998@gmail.com', '9312358347', 'ул. Меркулова, 33', '2023-11-06 16:24:00', '2023-11-11 10:19:00', 19700, 2],
            ['18845775208468184', 'starostin1988@gmail.com', '9312358347', 'ул. Меркулова, 33', '2023-11-11 15:14:00', '2023-11-13 13:21:00', 23300, 2],
            ['19426885376871915', 'vinogradovger1995@gmail.com', '9312358347', 'ул. Меркулова, 33', '2023-11-15 14:07:00', '2023-11-20 15:21:00', 42000, 2],
            ['16792670650274984', 'livova1991@gmail.com', '5574104392', 'ул. Неделина, 2В', '2023-11-18 13:01:00', '2023-11-24 17:42:00', 63000, 2],
            ['00461778306695595', 'vasilieva2000@gmail.com', '9312358347', 'ул. Меркулова, 33', '2023-11-20 12:05:00', '2023-11-20 12:30:00', 0, 0],
            ['20524622129160532', 'gavrilov1985@gmail.com', '6404561972', 'пл. Мира, 2', '2023-11-25 10:27:00', '2023-11-27 12:30:00', 8000, 2],
            ['08217591273370506', 'zaharov2001@gmail.com', '5085988456', 'ул. Валентины Терешковой, 11/1', '2023-11-28 09:27:00', '2023-12-01 15:38:00', 9400, 2],
            ['20524622129160532', 'gusev1960@gmail.com', '6404561972', 'пл. Мира, 2', '2023-12-02 10:14:00', '2023-12-07 10:14:00', 13000, 1],
            ['46972866401870844', 'baranov1999@gmail.com', '5085988456', 'ул. Валентины Терешковой, 11/1', '2023-12-03 16:43:00', '2023-12-06 16:43:00', 16500, 1],
            ['08217591273370506', 'zaharov2001@gmail.com', '5085988456', 'ул. Валентины Терешковой, 11/1', '2023-12-04 14:23:00', '2023-12-07 14:23:00', 7800, 1],
            ['11164288745437965', 'vinogradovger1995@gmail.com', '6404561972', 'пл. Мира, 2', '2023-12-05 09:13:00', '2023-12-07 09:13:00', 12100, 1],
            ['73329066390099932', 'nikulin1990@gmail.com', '6404561972', 'пл. Мира, 2', '2023-12-05 12:08:00', '2023-12-08 12:08:00', 19000, 1],
            ['77222665727084389', 'zaiceva1996@gmail.com', '5920248763', 'ул. Московской, 83', '2023-12-05 12:15:00', '2023-12-08 12:15:00', 40000, 1],
            ['63192064816322174', 'gavrilov1985@gmail.com', '5920248763', 'ул. Московской, 83', '2023-12-05 12:40:00', '2023-12-10 12:40:00', 32500, 1],
            ['00461778306695595', 'livova1991@gmail.com', '9312358347', 'ул. Меркулова, 33', '2023-12-05 13:01:00', '2023-12-05 13:01:00', 0, 0],
        ];

        foreach($bookingArray as $item){
            $booking = new Booking();
            $booking->setCar($manager->getRepository(Car::class)->findBy(["car_vin" => $item[0]])[0])
                    ->setClient($manager->getRepository(Client::class)->findBy(["clt_email" => $item[1]])[0])
                    ->setManager($manager->getRepository(Manager::class)->findBy(["mng_passport_details" => $item[2]])[0])
                    ->setStationService($manager->getRepository(StationService::class)->findBy(["stn_address" => $item[3]])[0])
                    ->setBkgDateBegin(DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $item[4]))
                    ->setBkgDateEnd(DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $item[5]))
                    ->setBkgCost($item[6])
                    ->setBkgStatus($item[7]);
            $manager->persist($booking);
        }
        $manager->flush();
    }
}
