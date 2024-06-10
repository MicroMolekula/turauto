<?php

namespace App\Controller;

use App\Entity\AddService;
use App\Entity\Car;
use App\Entity\CarClass;
use App\Entity\StationService;
use App\Repository\CarRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/car')]
class CarController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response
    {
        /*
        0 - Удалена из каталога
        1 - В аренде
        2 - Находится в пункте выдачи
        3 - На ремонте
        */
        $cars = $carRepository->findAll();
        $carsResponse = [];
        foreach ($cars as $car) {
            $addServicesArr = [];
            $addServices = $car->getAddServices();
            foreach ($addServices as $addService) {
                $addServicesArr[] = $addService->getSrvType();
            }
            $carsResponse[] = [
                "id" => $car->getCarVin(),
                "mark" => $car->getCarMake(),
                "model" => $car->getCarModel(),
                "date" => $car->getCarDateOfIssue()->format('Y-m-d'),
                "state_number" => $car->getCarStateNumber(),
                "cls_title" => $car->getCarClass()->getClsTitle(),
                "image" => $car->getCarImg(),
                "station_service" => $car->getStationService()->getStnAddress(),
                "body_type" => $car->getCarBodyType(),
                "color" => $car->getCarColor(),
                "status" => $car->getCarStatus(),
                "gearbox" => $car->getCarGearboxType(),
                "add_services" => $addServicesArr,
            ];
        }
        return $this->json($carsResponse);
    }

    #[Route('/for_client', methods: ['GET'])]
    public function cars(CarRepository $carRepository): Response
    {
        /*
        0 - Удалена из каталога
        1 - В аренде
        2 - Находится в пункте выдачи
        3 - На ремонте
        */
        $cars = $carRepository->findAll();
        $carsResponse = [];
        foreach ($cars as $car) {
            if ($car->getCarStatus() == 2) {
                $addServicesArr = [];
                $addServices = $car->getAddServices();
                foreach ($addServices as $addService) {
                    $addServicesArr[] = $addService->getSrvType();
                }
                $cost =  $car->getCarClass()->getClsCoefCost()*2000;
                foreach ($addServices as $addService) {
                    $cost += $addService->getSrvCost();
                }
                
                $carsResponse[] = [
                    "id" => $car->getCarVin(),
                    "mark" => $car->getCarMake(),
                    "model" => $car->getCarModel(),
                    "date" => $car->getCarDateOfIssue()->format('Y-m-d'),
                    "state_number" => $car->getCarStateNumber(),
                    "cls_title" => $car->getCarClass()->getClsTitle(),
                    "image" => $car->getCarImg(),
                    "station_service" => $car->getStationService()->getStnAddress(),
                    "body_type" => $car->getCarBodyType(),
                    "color" => $car->getCarColor(),
                    "status" => $car->getCarStatus(),
                    "gearbox" => $car->getCarGearboxType(),
                    "add_services" => $addServicesArr,
                    "cost" => $cost,
                ];
            }
        }
        return $this->json($carsResponse);
    }

    #[Route('/new', methods: ["POST"])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $car = new Car();
        $car->setCarVin($request['id'])
            ->setCarMake($request['mark'])
            ->setCarModel($request['model'])
            ->setCarDateOfIssue(DateTimeImmutable::createFromFormat('Y-m-d', $request['date']))
            ->setCarClass($manager->getRepository(CarClass::class)->findBy(['cls_title' => $request['cls_title']])[0])
            ->setCarImg($request['image'])
            ->setCarStateNumber($request['state_number'])
            ->setCarGearboxType($request['gearbox'])
            ->setCarColor($request['color'])
            ->setCarStatus($request['status'])
            ->setStationService($manager->getRepository(StationService::class)->findBy(['stn_address' => $request['station_service']])[0])
            ->setCarBodyType($request['body_type']);
        foreach ($request['add_services'] as $ads) {
            $addService = $manager->getRepository(AddService::class)->findOneBy(['srv_type' => $ads]);
            $car->addAddService($addService);
        }
        $manager->persist($car);
        $manager->flush();
        return $this->json($request);
    }

    #[Route('/{car_vin}/edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, string $car_vin): Response
    {
        $request = $request->toArray();
        $car = $manager->getRepository(Car::class)->findBy(['car_vin' => $car_vin])[0];
        if ($car) {
            $car->setCarMake($request['mark'])
                ->setCarModel($request['model'])
                ->setCarDateOfIssue(DateTimeImmutable::createFromFormat('Y-m-d', $request['date']))
                ->setCarClass($manager->getRepository(CarClass::class)->findBy(['cls_title' => $request['cls_title']])[0])
                ->setCarImg($request['image'])
                ->setCarStateNumber($request['state_number'])
                ->setCarGearboxType($request['gearbox'])
                ->setCarColor($request['color'])
                ->setCarStatus($request['status'])
                ->setStationService($manager->getRepository(StationService::class)->findBy(['stn_address' => $request['station_service']])[0])
                ->setCarBodyType($request['body_type']);
            foreach ($request['add_services'] as $ads) {
                $addService = $manager->getRepository(AddService::class)->findOneBy(['srv_type' => $ads]);
                $car->addAddService($addService);
            }
            $manager->persist($car);
            $manager->flush();
            return $this->json($request);
        }
        return $this->json(['error' => 'Такого автомобиля не существует']);
    }

    #[Route('/{car_vin}/delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $manager, string $car_vin)
    {
        $car = $manager->getRepository(Car::class)->findBy(['car_vin' => $car_vin])[0];
        if ($car) {
            $manager->remove($car);
            $manager->flush();
            return $this->json(['ok' => "Автомобиль удален"]);
        }
        return $this->json(['error' => 'Такого автомобиля не существует']);
    }

    #[Route('/upload', methods: ['POST'])]
    public function upload(Request $request)
    {
        $file = $request->files->get('demo')[0];
        if ($file) {
            $filename = $file->getClientOriginalName();
            $file->move($this->getParameter('upload_directory'), $filename);
            return $this->json(["path" => "/img/cars/" . "$filename"]);
        }
        return $this->json(["er" => $file]);
    }

    #[Route('/report', methods: ['GET'])]
    public function report(EntityManagerInterface $manager): Response
    {
        $cars = $manager->getRepository(Car::class)->report();
        return $this->json($cars);
    }
}
