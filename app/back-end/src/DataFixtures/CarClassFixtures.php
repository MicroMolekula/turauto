<?php

namespace App\DataFixtures;

use App\Entity\CarClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarClassFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $carClassArray = [
            ['A', 0.8, 1, 'B', 5000],
            ['B', 1, 2, 'B', 5000],
            ['C', 1.4, 3, 'B', 4500],
            ['D', 2, 4, 'B', 4000],
            ['E', 3, 6, 'B', 3000],
            ['M', 2, 4, 'D1', 4000],
            ['J', 1.5, 3, 'B', 5000],
        ];

        foreach($carClassArray as $item){
            $carClass = new CarClass();
            $carClass->setClsCategory($item[3])
                     ->setClsCoefCost($item[1])
                     ->setClsDrvExp($item[2])
                     ->setClsTitle($item[0])
                     ->setClsMileageLimit($item[4]);
            $manager->persist($carClass);
        }

        $manager->flush();
    }
}
