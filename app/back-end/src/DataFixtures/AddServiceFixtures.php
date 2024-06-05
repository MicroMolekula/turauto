<?php

namespace App\DataFixtures;

use App\Entity\AddService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $addServiceArray = [
            ['Подогрев руля', 3000],
            ['Подогрев сидений', 4000],
            ['Камера заднего вида', 5000],
            ['Круиз-контроль', 5000],
            ['Климат-контроль', 5000],
            ['Крепления детских сидений', 1500],
            ['Датчики парковки', 2000],
            ['Обогрев лобового стекла', 1500],
        ];

        foreach($addServiceArray as $item){
            $addService = new AddService();
            $addService->setSrvType($item[0])
                       ->setSrvCost($item[1]);
            $manager->persist($addService);
        }

        $manager->flush();
    }
}
