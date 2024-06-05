<?php

namespace App\DataFixtures;

use App\Entity\StationService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StationServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $stationServiceArray = [
            ['пл. Мира, 2', '9207209834', '08:00:00', '20:00:00'],
            ['ул. Неделина, 2В', '9203041843', '09:00:00', '21:00:00'],
            ['ул. Валентины Терешковой, 11/1', '9204041735', '11:00:00', '23:00:00'],
            ['ул. Меркулова, 33', '9204041735', '8:00:00', '18:00:00'],
            ['ул. Московской, 83', '9203901033', '8:00:00', '20:00:00'],
        ];

        foreach($stationServiceArray as $item){
            $stationService = new StationService;
            $stationService->setStnAddress($item[0])
                           ->setStnPhone($item[1])
                           ->setStnTimeBegin(DateTimeImmutable::createFromFormat('H:i:s', $item[2]))
                           ->setStnTimeEnd(DateTimeImmutable::createFromFormat('H:i:s', $item[3]));
            $manager->persist($stationService);
        }
        $manager->flush();
    }
}
