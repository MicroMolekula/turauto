<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Manager;
use App\Entity\StationService;

class ManagerFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $managerArray = [
            ['пл. Мира, 2', 'Соловьева', 'Валерия', 'Кирилловна', '6404561972', 'qwe123', 'manager', 'solovieva@mail.com'],
            ['ул. Неделина, 2В', 'Смирнов', 'Демид', 'Егорович', '5574104392', '32ras', 'manager', 'smirnov22@gmail.com'],
            ['ул. Валентины Терешковой, 11/1', 'Данилова', 'Вероника', 'Богдановна', '5085988456', 'abc321', 'manager', 'danilov332@mail.ru'],
            ['ул. Меркулова, 33', 'Иванова', 'София', 'Дмитриевна',  '9312358347', '321qqq', 'manager', 'ivanov112@email.ru'],
            ['ул. Московской, 83', 'Кузина', 'Арина', 'Максимовна', '5920248763', '123456', 'manager', 'kuzina314@mail.com'],
            ['пл. Мира, 2', 'Красиков', 'Иван', 'Александрович', '9508081037', 'luke228', 'admin', 'ivan1.krasikov.2003@gmail.com'],
        ];

        foreach($managerArray as $item){
            $managerObject = new Manager();
            $managerObject->setMngSurname($item[1])
                          ->setMngName($item[2])
                          ->setMngMidlename($item[3])
                          ->setMngPassportDetails($item[4])
                          ->setStationService($manager->getRepository(StationService::class)->findBy(["stn_address" => $item[0]])[0]);
            $password = $this->hasher->hashPassword($managerObject, $item[5]);
            $managerObject->setMngPassword($password)
                          ->setMngRole($item[6])
                          ->setMngEmail($item[7]);
            $manager->persist($managerObject);
        }
        $manager->flush();
    }
}
