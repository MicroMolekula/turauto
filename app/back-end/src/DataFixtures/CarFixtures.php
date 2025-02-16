<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\CarClass;
use App\Entity\StationService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

ini_set("memory_limit", 4 * 1024 * 1024);

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $carArray = [
            ['Daewoo', 'Matiz', '2012-01-01', 'С149ТЕ48', '41947609732412329', 'A', 'пл. Мира, 2', 'Хэтчбек', 'Красный', 2, 'МКПП', "/img/cars/matiz_red.jpg"],
            ['Renault', 'Logan', '2015-01-01', 'Т466ХА48', '20524622129160532', 'B', 'пл. Мира, 2', 'Седан', 'Черный', 1, 'МКПП', "/img/cars/logan_black.jpg"],
            ['Hyundai', 'Solaris', '2014-01-01', 'Е111СО48', '46998607911885010', 'B', 'ул. Неделина, 2В', 'Седан', 'Синий', 2, 'МКПП', "/img/cars/solaris_blue.jpg"],
            ['Volkswagen', 'Polo', '2016-01-01', 'Р319ОН48', '16623052788306146', 'B', 'ул. Неделина, 2В', 'Xэтчбек', 'Серый', 3, 'МКПП', "/img/cars/polo_gray.jpg"],
            ['Volkswagen', 'Polo', '2017-01-01', 'М363РЕ48', '46972866401870844', 'B', 'ул. Валентины Терешковой, 11/1', 'Xэтчбек', 'Серый', 1, 'МКПП', "/img/cars/polo_gray2.jpg"],
            ['KIA', 'Picanto', '2016-01-01', 'Е481УВ48', '08217591273370506', 'A', 'ул. Валентины Терешковой, 11/1', 'Xэтчбек', 'Белый', 1, 'АКПП', "/img/cars/picanto_white.jpg"],
            ['Audi', 'A3 Sedan', '2018-01-01', 'А627СО48', '93876883250772029', 'C', 'ул. Меркулова, 33', 'Седан', 'Синий', 0, 'АКПП', "/img/cars/a3_blue.jpg"],
            ['Audi', 'A3 Sedan', '2018-01-01', 'Н797ТМ48', '18845775208468184', 'C', 'ул. Меркулова, 33', 'Седан', 'Белый', 2, 'АКПП', "/img/cars/a3_white.jpg"],
            ['Toyota', 'Corolla', '2017-01-01', 'Т202КА48', '11164288745437965', 'C', 'пл. Мира, 2', 'Седан', 'Черный', 1, 'МКПП', "/img/cars/corolla_black.jpg"],
            ['Toyota', 'Camry', '2019-01-01', 'С190ЕЕ48', '19426885376871915', 'D', 'ул. Московской, 83', 'Седан', 'Черный', 2, 'АКПП', "/img/cars/camry_black.jpg"],
            ['Audi', 'A4', '2018-01-01', 'Н150КХ48', '73329066390099932', 'D', 'ул. Валентины Терешковой, 11/1', 'Седан', 'Черный', 1, 'РКПП', "/img/cars/a4_black.png"],
            ['Audi', 'A6', '2020-01-01', 'М805ТС48', '16792670650274984', 'E', 'ул. Неделина, 2В', 'Седан', 'Черный', 2, 'РКПП', "/img/cars/a6_black.png"],
            ['Volvo', 'S90', '2021-01-01', 'С803РТ48', '77222665727084389', 'E', 'ул. Московской, 83', 'Седан', 'Белый', 1, 'АКПП', "/img/cars/s90_white.jpg"],
            ['Volkswagen', 'Caravelle Long', '2021-01-01', 'Т371ВТ48', '63192064816322174', 'M', 'ул. Московской, 83', 'Минивэн', 'Черный', 1, 'РКПП', "/img/cars/carav_black.jpg"],
            ['Jeep', 'Wrangler', '2016-01-01', 'С734МА48', '00461778306695595', 'J', 'ул. Меркулова, 33', 'Внедорожник', 'Черный', 2, 'АКПП', "/img/cars/jeep_black.jpg"],
        ];

        foreach($carArray as $item){
            $car = new Car();
            $car->setCarVin($item[4])
                ->setCarMake($item[0])
                ->setCarModel($item[1])
                ->setCarDateOfIssue(DateTimeImmutable::createFromFormat("Y-m-d", $item[2]))
                ->setCarStateNumber($item[3])
                ->setCarClass($manager->getRepository(CarClass::class)->findBy(["cls_title" => $item[5]])[0])
                ->setStationService($manager->getRepository(StationService::class)->findBy(["stn_address" => $item[6]])[0])
                ->setCarBodyType($item[7])
                ->setCarColor($item[8])
                ->setCarStatus($item[9])
                ->setCarGearboxType($item[10])
                ->setCarImg($item[11]);
            $manager->persist($car);
        }
        $manager->flush();
    }
}
