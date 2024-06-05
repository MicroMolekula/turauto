<?php

namespace App\Controller;

use App\Entity\StationService;
use App\Repository\StationServiceRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/station_service')]
class StationServiceController extends AbstractController
{
    #[Route('/', methods:['GET'])]
    public function index(StationServiceRepository $stationServiceRepository): Response
    {
        $stationsService = $stationServiceRepository->findAll();
        $response = [];
        foreach($stationsService as $stationService){
            $response[] = [
                "id" => $stationService->getStnId(),
                "address" => $stationService->getStnAddress(),
                "phone" => $stationService->getStnPhone(),
                "date_begin" => $stationService->getStnTimeBegin()->format("H:i:s"),
                "date_end" => $stationService->getStnTimeEnd()->format("H:i:s"),
            ];
        }
        return $this->json($response);
    }

    #[Route('/new', methods:['POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $stationService = new StationService();
        $stationService->setStnAddress($request['address'])
                       ->setStnPhone($request['phone'])
                       ->setStnTimeBegin(DateTimeImmutable::createFromFormat("H:i:s", $request['date_begin']))
                       ->setStnTimeEnd(DateTimeImmutable::createFromFormat("H:i:s", $request['date_end']));
        $manager->persist($stationService);
        $manager->flush();
        return $this->json($request);
    }

    #[Route('/{stn_id}/edit', methods:['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, int $stn_id): Response
    {
        $request = $request->toArray();
        $stationService = $manager->getRepository(StationService::class)->findBy(["stn_id" => $stn_id])[0];
        if($stationService){
            $stationService->setStnAddress($request['address'])
                       ->setStnPhone($request['phone'])
                       ->setStnTimeBegin(DateTimeImmutable::createFromFormat("H:i:s", $request['date_begin']))
                       ->setStnTimeEnd(DateTimeImmutable::createFromFormat("H:i:s", $request['date_end']));
            $manager->persist($stationService);
            $manager->flush();
            return $this->json($request);
        }
        return $this->json(["error" => "Элемента не существует"]);
    }

    #[Route('/{stn_id}/delete', methods:['DELETE'])]
    public function delete(EntityManagerInterface $manager, int $stn_id): Response
    {
        $stationService = $manager->getRepository(StationService::class)->findBy(["stn_id" => $stn_id])[0];
        if($stationService){
            $manager->remove($stationService);
            $manager->flush();
            return $this->json(["ok" => "Элемент удален"]);
        }
        return $this->json(["error" => "Элемента не существует"]);
    }

}
