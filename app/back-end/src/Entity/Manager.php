<?php

namespace App\Entity;

use App\Repository\ManagerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ManagerRepository::class)]
#[ORM\Table(name:"manager")]
class Manager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $mng_id;

    #[ORM\Column(length: 32)]
    private ?string $mng_surname;

    #[ORM\Column(length: 32)]
    private ?string $mng_name;

    #[ORM\Column(length: 32)]
    private ?string $mng_midlename;

    #[ORM\Column(length: 10)]
    private ?string $mng_passport_details;

    #[ORM\Column(length: 255)]
    private ?string $mng_password;

    #[ORM\Column]
    private ?int $stn_id;

    #[ORM\OneToMany(targetEntity:Booking::class, mappedBy:"manager")]
    private ?ArrayCollection $bookings;

    #[ORM\ManyToOne(targetEntity:StationService::class, inversedBy:"managers")]
    #[ORM\JoinColumn(name:"stn_id", referencedColumnName:"stn_id")]
    private ?StationService $station_service;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->mng_id;
    }

    public function getMngSurname(): ?string
    {
        return $this->mng_surname;
    }

    public function setMngSurname(string $mng_surname): static
    {
        $this->mng_surname = $mng_surname;

        return $this;
    }

    public function getMngName(): ?string
    {
        return $this->mng_name;
    }

    public function setMngName(string $mng_name): static
    {
        $this->mng_name = $mng_name;

        return $this;
    }

    public function getMngMidlename(): ?string
    {
        return $this->mng_midlename;
    }

    public function setMngMidlename(string $mng_midlename): static
    {
        $this->mng_midlename = $mng_midlename;

        return $this;
    }

    public function getMngPassportDetails(): ?string
    {
        return $this->mng_passport_details;
    }

    public function setMngPassportDetails(string $mng_passport_details): static
    {
        $this->mng_passport_details = $mng_passport_details;

        return $this;
    }

    public function getMngPassword(): ?string
    {
        return $this->mng_password;
    }

    public function setMngPassword(string $mng_password): static
    {
        $this->mng_password = $mng_password;

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

    public function getMngId(): ?int
    {
        return $this->mng_id;
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
            $booking->setManager($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getManager() === $this) {
                $booking->setManager(null);
            }
        }

        return $this;
    }
}
