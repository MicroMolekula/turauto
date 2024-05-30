<?php

namespace App\Entity;

use App\Repository\StationServiceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: StationServiceRepository::class)]
#[ORM\Table(name:"station_service")]
class StationService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $stn_id;

    #[ORM\Column(length:64)]
    private ?string $stn_address;

    #[ORM\Column(length:10)]
    private ?string $stn_phone;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $stn_time_begin;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $stn_time_end;

    #[ORM\OneToMany(targetEntity:Booking::class, mappedBy:"station_service")]
    private ?ArrayCollection $bookings;

    #[ORM\OneToMany(targetEntity:Car::class, mappedBy:"station_service")]
    private ?ArrayCollection $cars;

    #[ORM\OneToMany(targetEntity:Manager::class, mappedBy:"station_service")]
    private ?ArrayCollection $managers;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->cars = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->stn_id;
    }

    public function getStnTimeBegin(): ?\DateTimeInterface
    {
        return $this->stn_time_begin;
    }

    public function setStnTimeBegin(\DateTimeInterface $stn_time_begin): static
    {
        $this->stn_time_begin = $stn_time_begin;

        return $this;
    }

    public function getStnId(): ?int
    {
        return $this->stn_id;
    }

    public function getStnAddress(): ?string
    {
        return $this->stn_address;
    }

    public function setStnAddress(string $stn_address): static
    {
        $this->stn_address = $stn_address;

        return $this;
    }

    public function getStnPhone(): ?string
    {
        return $this->stn_phone;
    }

    public function setStnPhone(string $stn_phone): static
    {
        $this->stn_phone = $stn_phone;

        return $this;
    }

    public function getStnTimeEnd(): ?\DateTimeInterface
    {
        return $this->stn_time_end;
    }

    public function setStnTimeEnd(\DateTimeInterface $stn_time_end): static
    {
        $this->stn_time_end = $stn_time_end;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setStationService($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getStationService() === $this) {
                $booking->setStationService(null);
            }
        }

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
            $car->setStationService($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getStationService() === $this) {
                $car->setStationService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Manager>
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(Manager $manager): static
    {
        if (!$this->managers->contains($manager)) {
            $this->managers->add($manager);
            $manager->setStationService($this);
        }

        return $this;
    }

    public function removeManager(Manager $manager): static
    {
        if ($this->managers->removeElement($manager)) {
            // set the owning side to null (unless already changed)
            if ($manager->getStationService() === $this) {
                $manager->setStationService(null);
            }
        }

        return $this;
    }
}
