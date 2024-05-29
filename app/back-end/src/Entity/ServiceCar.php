<?php

namespace App\Entity;

use App\Repository\ServiceCarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceCarRepository::class)]
#[ORM\Table(name:"service_car")]
class ServiceCar
{
    #[ORM\Id]
    #[ORM\Column(length:17)]
    private ?string $car_vin;

    #[ORM\Id]
    #[ORM\Column(length:32)]
    private ?string $srv_type;

    public function getCarVin(): ?int
    {
        return $this->car_vin;
    }

    public function setCarVin(string $car_vin) : static
    {
        $this->car_vin = $car_vin;
        return $this;
    }

    public function getSrvType(): ?string
    {
        return $this->srv_type;
    }

    public function setSrvType(string $srv_type): static
    {
        $this->srv_type = $srv_type;
        return $this;
    }
}
