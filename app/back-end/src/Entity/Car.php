<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\Table(name:"car")]
class Car
{
    #[ORM\Id]
    #[ORM\Column(length:17)]
    private ?string $car_vin;

    #[ORM\Column(length:32)]
    private ?string $car_make;

    #[ORM\Column(length:32)]
    private ?string $car_model;

    #[ORM\Column]
    private ?\DateTimeImmutable $car_date_of_issue;

    #[ORM\Column(length:8)]
    private ?string $car_state_number;

    #[ORM\Column(length:32)]
    private ?string $cls_title;

    #[ORM\Column]
    private ?int $stn_id;

    #[ORM\Column(length:64)]
    private ?string $car_body_type;

    #[ORM\Column(length:32)]
    private ?string $car_color;

    #[ORM\Column(name:"car_status", columnDefinition:" INT check (car_status between 0 and 3)")]
    private ?int $car_status;

    #[ORM\Column(length:16)]
    private ?string $car_gearbox_type;

    #[ORM\Column(length:128)]
    private ?string $car_img;

    #[ORM\OneToMany(targetEntity:Booking::class, mappedBy:"car")]
    private ?Collection $bookings;

    #[ORM\ManyToOne(targetEntity:StationService::class, inversedBy:"cars")]
    #[ORM\JoinColumn(name:"stn_id", referencedColumnName:"stn_id")]
    private ?StationService $station_service;

    #[ORM\ManyToOne(targetEntity:CarClass::class, inversedBy:"cars")]
    #[ORM\JoinColumn(name:"cls_title", referencedColumnName:"cls_title")]
    private ?CarClass $car_class;

    #[ORM\ManyToMany(targetEntity:AddService::class, mappedBy:"cars")]
    private ?Collection $add_services;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->add_services = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->car_vin;
    }

    public function getCarVin(): ?string
    {
        return $this->car_vin;
    }

    public function setCarVin(string $car_vin): static
    {
        $this->car_vin = $car_vin;

        return $this;
    }

    public function getCarMake(): ?string
    {
        return $this->car_make;
    }

    public function setCarMake(string $car_make): static
    {
        $this->car_make = $car_make;

        return $this;
    }

    public function getCarModel(): ?string
    {
        return $this->car_model;
    }

    public function setCarModel(string $car_model): static
    {
        $this->car_model = $car_model;

        return $this;
    }

    public function getCarDateOfIssue(): ?\DateTimeImmutable
    {
        return $this->car_date_of_issue;
    }

    public function setCarDateOfIssue(\DateTimeImmutable $car_date_of_issue): static
    {
        $this->car_date_of_issue = $car_date_of_issue;

        return $this;
    }

    public function getCarStateNumber(): ?string
    {
        return $this->car_state_number;
    }

    public function setCarStateNumber(string $car_state_number): static
    {
        $this->car_state_number = $car_state_number;

        return $this;
    }

    public function getClsTitle(): ?string
    {
        return $this->cls_title;
    }

    public function setClsTitle(string $cls_title): static
    {
        $this->cls_title = $cls_title;

        return $this;
    }

    public function getStnId(): ?int
    {
        return $this->stn_id;
    }

    public function setStnId(int $stn_id): static
    {
        $this->stn_id = $stn_id;

        return $this;
    }

    public function getCarBodyType(): ?string
    {
        return $this->car_body_type;
    }

    public function setCarBodyType(string $car_body_type): static
    {
        $this->car_body_type = $car_body_type;

        return $this;
    }

    public function getCarColor(): ?string
    {
        return $this->car_color;
    }

    public function setCarColor(string $car_color): static
    {
        $this->car_color = $car_color;

        return $this;
    }

    public function getCarStatus(): ?int
    {
        return $this->car_status;
    }

    public function setCarStatus(int $car_status): static
    {
        $this->car_status = $car_status;

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
            $booking->setCar($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getCar() === $this) {
                $booking->setCar(null);
            }
        }

        return $this;
    }

    public function getStationService(): ?StationService
    {
        return $this->station_service;
    }

    public function setStationService(?StationService $station_service): static
    {
        $this->station_service = $station_service;

        return $this;
    }

    public function getCarClass(): ?CarClass
    {
        return $this->car_class;
    }

    public function setCarClass(?CarClass $car_class): static
    {
        $this->car_class = $car_class;

        return $this;
    }

    /**
     * @return Collection<int, AddService>
     */
    public function getAddServices(): Collection
    {
        return $this->add_services;
    }

    public function addAddService(AddService $addService): static
    {
        if (!$this->add_services->contains($addService)) {
            $this->add_services->add($addService);
            $addService->addCar($this);
        }

        return $this;
    }

    public function removeAddService(AddService $addService): static
    {
        if ($this->add_services->removeElement($addService)) {
            $addService->removeCar($this);
        }

        return $this;
    }

    public function getCarImg(): ?string
    {
        return $this->car_img;
    }

    public function setCarImg(string $car_img): static
    {
        $this->car_img = $car_img;

        return $this;
    }

    public function getCarGearboxType(): ?string
    {
        return $this->car_gearbox_type;
    }

    public function setCarGearboxType(string $car_gearbox_type): static
    {
        $this->car_gearbox_type = $car_gearbox_type;

        return $this;
    }
}
