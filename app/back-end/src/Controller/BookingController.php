<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Car;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;

#[Route('/booking')]
class BookingController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(EntityManagerInterface $manager): Response
    {
        $bookings = $manager->getRepository(Booking::class)->findAll();
        $response = [];

        foreach ($bookings as $booking) {
            $response[] = [
                "id" => $booking->getBkgId(),
                "car" => [
                    "id" => $booking->getCar()->getCarVin(),
                    "mark" => $booking->getCar()->getCarMake(),
                    "model" => $booking->getCar()->getCarModel(),
                    "date" => $booking->getCar()->getCarDateOfIssue(),
                    "state_number" => $booking->getCar()->getCarStateNumber(),
                ],
                "client" => [
                    "id" => $booking->getClient()->getId(),
                    "surname" => $booking->getClient()->getCltSurname(),
                    "name" => $booking->getClient()->getCltName(),
                    "middlename" => $booking->getClient()->getCltMidlename(),
                    "email" => $booking->getClient()->getCltEmail(),
                ],
                "manager" => [
                    "id" => $booking->getManager()->getId(),
                    "surname" => $booking->getManager()->getMngSurname(),
                    "name" => $booking->getManager()->getMngName(),
                    "middlename" => $booking->getManager()->getMngMidlename(),
                    "email" => $booking->getManager()->getMngEmail(),
                ],
                "station_service" => $booking->getStationService()->getStnAddress(),
                "date_begin" => $booking->getBkgDateBegin()->format("Y-m-d H:i:s"),
                "date_end" => $booking->getBkgDateEnd()->format("Y-m-d H:i:s"),
                "cost" => $booking->getBkgCost(),
                "status" => $booking->getBkgStatus(),
            ];
        }
        return $this->json($response);
    }

    #[Route('/new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $car = $manager->getRepository(Car::class)->findOneBy(['car_vin' => $request['car']]);
        $client = $manager->getRepository(Client::class)->findOneBy(['clt_id' => $request['client']]);
        $carBkg = $manager->getRepository(Booking::class)->addNew($client, $car, (int)$request['count_day']);
        $bookings = $manager->getRepository(Booking::class)->findBy(['car_vin' => $carBkg->getCarVin()]);
        foreach ($bookings as $booking) {
            if ($booking->getBkgStatus() == 1) {
                return $this->json([
                    'status' => 'ok',
                    "id" => $booking->getBkgId(),
                    "car" => [
                        "id" => $booking->getCar()->getCarVin(),
                        "mark" => $booking->getCar()->getCarMake(),
                        "model" => $booking->getCar()->getCarModel(),
                        "date" => $booking->getCar()->getCarDateOfIssue(),
                        "state_number" => $booking->getCar()->getCarStateNumber(),
                    ],
                    "client" => [
                        "id" => $booking->getClient()->getId(),
                        "surname" => $booking->getClient()->getCltSurname(),
                        "name" => $booking->getClient()->getCltName(),
                        "middlename" => $booking->getClient()->getCltMidlename(),
                        "email" => $booking->getClient()->getCltEmail(),
                    ],
                    "manager" => [
                        "id" => $booking->getManager()->getId(),
                        "surname" => $booking->getManager()->getMngSurname(),
                        "name" => $booking->getManager()->getMngName(),
                        "middlename" => $booking->getManager()->getMngMidlename(),
                        "email" => $booking->getManager()->getMngEmail(),
                    ],
                    "station_service" => $booking->getStationService()->getStnAddress(),
                    "date_begin" => $booking->getBkgDateBegin()->format("Y-m-d H:i:s"),
                    "date_end" => $booking->getBkgDateEnd()->format("Y-m-d H:i:s"),
                    "cost" => $booking->getBkgCost(),
                    "status" => $booking->getBkgStatus(),
                ]);
            }
        }
        return $this->json(['status' => 'error']);
    }

    #[Route('/{bkg_id}/edit', methods:['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, int $bkg_id)
    {
        $request = $request->toArray();
        $booking = $manager->getRepository(Booking::class)->findOneBy(['bkg_id' => $bkg_id]);
        if($booking){
            $booking->setBkgStatus($request['status']);
            $manager->persist($booking);
            $manager->flush();
            return $this->json($request);
        }
        return $this->json(['message' => 'error']);
    }

    #[Route('/{bkg_id}/delete', methods:['DELETE'])]
    public function delete(EntityManagerInterface $manager, int $bkg_id)
    {
        $booking = $manager->getRepository(Booking::class)->findOneBy(['bkg_id' => $bkg_id]);
        if($booking){ 
            $manager->remove($booking);
            $manager->flush();
            return $this->json(['status' => 'ok']);
        }
        return $this->json(['status' => 'error']);
    }
}
