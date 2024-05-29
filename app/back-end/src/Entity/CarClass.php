<?php

namespace App\Entity;

use App\Repository\CarClassRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CarClassRepository::class)]
#[ORM\Table(name:"car_class")]
class CarClass
{
    #[ORM\Id]
    #[ORM\Column(length:32)]
    private ?string $cls_title;

    #[ORM\Column(type:"decimal", precision:2)]
    private ?float $cls_coef_cost;

    #[ORM\Column(type:"decimal", precision:2)]
    private ?float $cls_drv_exp;

    #[ORM\Column(length:32)]
    private ?string $cls_category;

    #[ORM\Column]
    private ?int $cls_mileage_limit;

    #[ORM\OneToMany(targetEntity:Car::class, mappedBy:"car_class")]
    private ?ArrayCollection $cars;

    public function getId(): ?int
    {
        return $this->cls_title;
    }

    public function getClsTitle(): ?string
    {
        return $this->cls_title;
    }

    public function getClsCoefCost(): ?string
    {
        return $this->cls_coef_cost;
    }

    public function setClsCoefCost(string $cls_coef_cost): static
    {
        $this->cls_coef_cost = $cls_coef_cost;

        return $this;
    }

    public function getClsDrvExp(): ?string
    {
        return $this->cls_drv_exp;
    }

    public function setClsDrvExp(string $cls_drv_exp): static
    {
        $this->cls_drv_exp = $cls_drv_exp;

        return $this;
    }

    public function getClsCategory(): ?string
    {
        return $this->cls_category;
    }

    public function setClsCategory(string $cls_category): static
    {
        $this->cls_category = $cls_category;

        return $this;
    }

    public function getClsMileageLimit(): ?int
    {
        return $this->cls_mileage_limit;
    }

    public function setClsMileageLimit(int $cls_mileage_limit): static
    {
        $this->cls_mileage_limit = $cls_mileage_limit;

        return $this;
    }
}
