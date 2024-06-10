<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use LDAP\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/client')]
class ClientController extends AbstractController
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        $response = [];
        foreach ($clients as $client) {
            if ($client->getDeletedAt() == null) {
                $response[] = [
                    "id" => $client->getCltId(),
                    "surname" => $client->getCltSurname(),
                    "name" => $client->getCltName(),
                    "middlename" => $client->getCltMidlename(),
                    "email" => $client->getCltEmail(),
                    "passport_details" => $client->getCltPassportDetails(),
                    "num_drv_lic" => $client->getCltNumDrvLic(),
                    "category_drv_lic" => $client->getCltDrvLicCategory(),
                    "date_drv_lic" => $client->getCltDrvLicDate()->format("Y-m-d"),
                    "phone" => $client->getCltPhone(),
                ];
            }
        }
        return $this->json($response);
    }

    #[Route('/new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $client = $manager->getRepository(Client::class)->findOneBy(['clt_email' => $request['email']]);
        if (!$client) {
            $client = new Client();
            $client->setCltSurname($request['surname'])
                ->setCltName($request['name'])
                ->setCltMidlename($request['middlename'])
                ->setCltEmail($request['email'])
                ->setCltPassportDetails($request['passport_details'])
                ->setCltNumDrvLic($request['num_drv_lic'])
                ->setCltDrvLicCategory($request['category_drv_lic'])
                ->setCltDrvLicDate(DateTimeImmutable::createFromFormat('Y-m-d', $request['date_drv_lic']))
                ->setCltPhone($request['phone']);
            $password = $this->hasher->hashPassword($client, $request['password']);
            $client->setCltPassword($password);
            $manager->persist($client);
            $manager->flush();
            $client = $manager->getRepository(Client::class)->findOneBy(['clt_email' => $request['email']]);
            return $this->json([
                'status' => 'ok',
                'role' => 'client',
                'id' => $client->getId()
            ]);
        }
        return $this->json(['status' => 'error', 'message' => 'Пользователь с таким email уже существует']);
    }

    #[Route('/{clt_id}/edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, int $clt_id): Response
    {
        $request = $request->toArray();
        $client = $manager->getRepository(Client::class)->findBy(["clt_id" => $clt_id])[0];
        if ($client) {
            $client->setCltSurname($request['surname'])
                ->setCltName($request['name'])
                ->setCltMidlename($request['middlename'])
                ->setCltEmail($request['email'])
                ->setCltPassportDetails($request['passport_details'])
                ->setCltNumDrvLic($request['num_drv_lic'])
                ->setCltDrvLicCategory($request['category_drv_lic'])
                ->setCltDrvLicDate(DateTimeImmutable::createFromFormat("Y-m-d", $request['date_drv_lic']))
                ->setCltPhone($request['phone']);
            $manager->persist($client);
            $manager->flush();
            return $this->json($request);
        }
        return $this->json(['error' => "Такого клиента не существует"]);
    }

    #[Route('/{clt_id}/show', methods: ['GET'])]
    public function show(EntityManagerInterface $manager, int $clt_id): Response
    {
        $client = $manager->getRepository(Client::class)->findOneBy(['clt_id' => $clt_id]);
        return $this->json([
            "id" => $client->getCltId(),
            "surname" => $client->getCltSurname(),
            "name" => $client->getCltName(),
            "middlename" => $client->getCltMidlename(),
            "email" => $client->getCltEmail(),
            "passport_details" => $client->getCltPassportDetails(),
            "num_drv_lic" => $client->getCltNumDrvLic(),
            "category_drv_lic" => $client->getCltDrvLicCategory(),
            "date_drv_lic" => $client->getCltDrvLicDate()->format("Y-m-d"),
            "phone" => $client->getCltPhone(),
        ]);
    }

    #[Route('/{clt_id}/delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $manager, int $clt_id): Response
    {
        $client = $manager->getRepository(Client::class)->findBy(["clt_id" => $clt_id])[0];
        if ($client) {
            $dateTime = date('Y-m-d H:i:s');
            $client->setDeletedAt(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateTime));
            $manager->persist($client);
            $manager->flush();
            return $this->json(["ok" => "Клиент удален"]);
        }
        return $this->json(["error" => "Такого клиента не существует"]);
    }

    #[Route('/{clt_id}/bookings', methods:['GET'])]
    public function bookings(EntityManagerInterface $manager, int $clt_id)
    {
        $client = $manager->getRepository(Client::class)->findOneBy(['clt_id' => $clt_id]);
        $bookings = $client->getBookings();
        $response = [];
        foreach($bookings as $booking){
            $response[] = [
                "id" => $booking->getBkgId(),
                "car" => [
                    "id" => $booking->getCar()->getCarVin(),
                    "mark" => $booking->getCar()->getCarMake(),
                    "model" => $booking->getCar()->getCarModel(),
                    "date" => $booking->getCar()->getCarDateOfIssue(),
                    "state_number" => $booking->getCar()->getCarStateNumber(),
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
}
