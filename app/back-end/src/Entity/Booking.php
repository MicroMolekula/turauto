<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ORM\Table(name: "booking")]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $bkg_id;

    #[ORM\Column(length:17)]
    private ?string $car_vin;

    #[ORM\Column]
    private ?int $clt_id;

    #[ORM\Column]
    private ?int $mng_id;

    #[ORM\Column]
    private ?int $stn_id;

    #[ORM\Column]
    private  ?\DateTimeImmutable $bkg_date_begin;

    #[ORM\Column]
    private ?\DateTimeImmutable $bkg_date_end;

    #[ORM\Column]
    private ?int $bkg_cost;

    #[ORM\Column(columnDefinition:"INT CHECK (0 <= bkg_status AND bkg_status >= 2)")]
    private ?int $bkg_status;

    #[ORM\ManyToOne(targetEntity:Car::class, inversedBy:"bookings")]
    #[ORM\JoinColumn(name:"car_vin", referencedColumnName:"car_vin")]
    private ?Car $car;

    #[ORM\ManyToOne(targetEntity:Client::class, inversedBy:"bookings")]
    #[ORM\JoinColumn(name:"clt_id", referencedColumnName:"clt_id")]
    private ?Client $client;

    #[ORM\ManyToOne(targetEntity:Manager::class, inversedBy:"bookings")]
    #[ORM\JoinColumn(name:"mng_id", referencedColumnName:"mng_id")]
    private ?Manager $manager;

    #[ORM\ManyToOne(targetEntity:StationService::class, inversedBy:"bookings")]
    #[ORM\JoinColumn(name:"stn_id", referencedColumnName:"stn_id")]
    private ?StationService $station_service;

    public function getBkgId(): int
    {
        return $this->bkg_id;
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

    public function getCltId(): ?int
    {
        return $this->clt_id;
    }

    public function setCltId(int $clt_id): static
    {
        $this->clt_id = $clt_id;
        return $this;
    }

    public function getMngId(): ?int
    {
        return $this->mng_id;
    }

    public function setMngId(int $mng_id): static
    {
        $this->mng_id = $mng_id;
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

    public function getBkgDateBegin(): ?\DateTimeImmutable
    {
        return $this->bkg_date_begin;
    }

    public function setBkgDateBegin(\DateTimeImmutable $bkg_date_begin): static
    {
        $this->bkg_date_begin = $bkg_date_begin;
        return $this;
    }

    public function getBkgDateEnd(): ?\DateTimeImmutable
    {
        return $this->bkg_date_end;
    }

    public function setBkgDateEnd(\DateTimeImmutable $bkg_date_end): static
    {
        $this->bkg_date_end = $bkg_date_end;
        return $this;
    }

    public function getBkgCost(): ?int
    {
        return $this->bkg_cost;
    }

    public function setBkgCost(int $bkg_cost): static
    {
        $this->bkg_cost = $bkg_cost;
        return $this;
    }

    public function getBkgStatus(): ?int
    {
        return $this->bkg_status;
    }

    public function setBkgStatus(int $bkg_status): static
    {
        $this->bkg_status = $bkg_status;
        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): static
    {
        $this->manager = $manager;

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
}
