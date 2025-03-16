<?php

namespace App\Service;

use App\DTO\CarsPage;
use App\Factory\CarFactory;
use App\Repository\CarRepository;

readonly class CarsService
{
    public function __construct(
        private CarRepository $carRepository,
        private CarFactory    $carFactory,
    ) {
    }

    public function getCars(int $page): CarsPage
    {
        $itemsPerPage = 9;
        $totalsItems = $this->carRepository->count();
        $countPage = ceil($totalsItems / $itemsPerPage);
        if ($page < 1 || $page > $countPage) {
            $page = $countPage;
        }
        $cars = $this->carRepository->getCarsWithPagination($page, $itemsPerPage);
        return new CarsPage(
            pages: [
                'totalPage' => $countPage,
                'currentPage' => $page,
                'totalItems' => $totalsItems,
            ],
            cars: $this->carFactory->createArrayResponse($cars),
        );
    }

}