<?php

namespace App\Factory;

use App\DTO\CarsResponse;
use App\Entity\Car;

class CarFactory
{
    public function createResponse(Car $car): CarsResponse
    {
        $addServicesArr = [];
        $addServices = $car->getAddServices();
        foreach ($addServices as $addService) {
            $addServicesArr[] = $addService->getSrvType();
        }
        $carReponse = new CarsResponse(
            id: $car->getCarVin(),
            mark: $car->getCarMake(),
            model: $car->getCarModel(),
            date: $car->getCarDateOfIssue()->format('Y-m-d'),
            state_number: $car->getCarStateNumber(),
            cls_title: $car->getCarClass()->getClsTitle(),
            image: $car->getCarImg(),
            station_service: $car->getStationService()->getStnAddress(),
            body_type: $car->getCarBodyType(),
            color: $car->getCarColor(),
            status: $car->getCarStatus(),
            gearbox: $car->getCarGearboxType(),
            add_services: $addServicesArr,
        );
        return $carReponse;
    }

    public function createArrayResponse(array $cars): array
    {
        $result = [];
        foreach ($cars as $car) {
            $result[] = $this->createResponse($car);
        }
        return $result;
    }
}