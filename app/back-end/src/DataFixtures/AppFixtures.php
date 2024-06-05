<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\CarClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        (new ClientFixtures($this->hasher))->load($manager);
        (new StationServiceFixtures())->load($manager);
        (new ManagerFixtures($this->hasher))->load($manager);
        (new CarClassFixtures())->load($manager);
        (new AddServiceFixtures())->load($manager);
        (new CarFixtures())->load($manager);
        (new ServiceCarFixtures())->load($manager);
        (new BookingFixtures())->load($manager);

        $manager->flush();
    }
}
