<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $clt_id;

    #[ORM\Column(length: 32)]
    private ?string $clt_surname;

    #[ORM\Column(length: 32)]
    private ?string $clt_name;

    #[ORM\Column(length: 32)]
    private ?string $clt_midlename;

    #[ORM\Column(length: 64)]
    private ?string $clt_email;

    #[ORM\Column(length: 10)]
    private ?string $clt_passport_details;

    #[ORM\Column(length: 10)]
    private ?string $clt_num_drv_lic;

    #[ORM\Column(length: 32)]
    private ?string $clt_drv_lic_category;

    #[ORM\Column]
    private ?\DateTimeImmutable $clt_drv_lic_date;

    #[ORM\Column(length: 10)]
    private ?string $clt_phone;

    #[ORM\Column(length: 255)]
    private ?string $clt_password;

    #[ORM\Column(nullable:true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\OneToMany(targetEntity:Booking::class, mappedBy:"client")]
    private ?Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->clt_id;
    }

    public function getCltSurname(): ?string
    {
        return $this->clt_surname;
    }

    public function setCltSurname(string $clt_surname): static
    {
        $this->clt_surname = $clt_surname;

        return $this;
    }

    public function getCltName(): ?string
    {
        return $this->clt_name;
    }

    public function setCltName(string $clt_name): static
    {
        $this->clt_name = $clt_name;

        return $this;
    }

    public function getCltMidlename(): ?string
    {
        return $this->clt_midlename;
    }

    public function setCltMidlename(string $clt_midlename): static
    {
        $this->clt_midlename = $clt_midlename;

        return $this;
    }

    public function getCltEmail(): ?string
    {
        return $this->clt_email;
    }

    public function setCltEmail(string $clt_email): static
    {
        $this->clt_email = $clt_email;

        return $this;
    }

    public function getCltPassportDetails(): ?string
    {
        return $this->clt_passport_details;
    }

    public function setCltPassportDetails(string $clt_passport_details): static
    {
        $this->clt_passport_details = $clt_passport_details;

        return $this;
    }

    public function getCltNumDrvLic(): ?string
    {
        return $this->clt_num_drv_lic;
    }

    public function setCltNumDrvLic(string $clt_num_drv_lic): static
    {
        $this->clt_num_drv_lic = $clt_num_drv_lic;

        return $this;
    }

    public function getCltDrvLicCategory(): ?string
    {
        return $this->clt_drv_lic_category;
    }

    public function setCltDrvLicCategory(string $clt_drv_lic_category): static
    {
        $this->clt_drv_lic_category = $clt_drv_lic_category;

        return $this;
    }

    public function getCltDrvLicDate(): ?\DateTimeImmutable
    {
        return $this->clt_drv_lic_date;
    }

    public function setCltDrvLicDate(\DateTimeImmutable $clt_drv_lic_date): static
    {
        $this->clt_drv_lic_date = $clt_drv_lic_date;

        return $this;
    }

    public function getCltPhone(): ?string
    {
        return $this->clt_phone;
    }

    public function setCltPhone(string $clt_phone): static
    {
        $this->clt_phone = $clt_phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->clt_password;
    }

    public function setCltPassword(string $clt_password): static
    {
        $this->clt_password = $clt_password;

        return $this;
    }

    public function getCltId(): ?int
    {
        return $this->clt_id;
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
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }

    public function getCltPassword(): ?string
    {
        return $this->clt_password;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
