<?php

namespace App\Entity;

use App\Repository\AddServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddServiceRepository::class)]
#[ORM\Table(name: "add_service")]
class AddService
{
    #[ORM\Id]
    #[ORM\Column(length:32)]
    private string $srv_type;

    #[ORM\Column]
    private int $srv_cost;

    #[ORM\ManyToMany(targetEntity:Car::class, inversedBy:"add_services")]
    #[ORM\JoinTable(name:"service_car")]
    #[ORM\JoinColumn(name:"srv_type", referencedColumnName:"srv_type")]
    #[ORM\InverseJoinColumn(name:"car_vin", referencedColumnName:"car_vin")]
    private ?ArrayCollection $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    public function getSrvType(): string
    {
        return $this->srv_type;
    }

    public function setSrvType(string $srv_type): self
    {
        $this->srv_type = $srv_type;
        return $this;
    }

    public function getSrvCost(): int
    {
        return $this->srv_cost;
    }

    public function setSrvCost(int $srv_cost): self
    {
        $this->srv_cost = $srv_cost;
        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        $this->cars->removeElement($car);

        return $this;
    }
}
