<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\StationService;
use App\Repository\ManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manager')]
class ManagerController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    private RequestStack $requestStack;

    public function __construct(UserPasswordHasherInterface $hasher, RequestStack $requestStack)
    {
        $this->hasher = $hasher;
        $this->requestStack = $requestStack;
    }

    #[Route('/', methods: ["GET"])]
    public function index(ManagerRepository $managerRepository): Response
    {
        $managers = $managerRepository->findAll();
        $response = [];
        foreach ($managers as $manager) {
            $response[] = [
                "id" => $manager->getMngId(),
                "surname" => $manager->getMngSurname(),
                "name" => $manager->getMngName(),
                "middlename" => $manager->getMngMidlename(),
                "station_service" => $manager->getStationService()->getStnAddress(),
                "passport_details" => $manager->getMngPassportDetails(),
                "role" => $manager->getMngRole() === 'admin' ? "Администратор" : "Менеджер",
                "email" => $manager->getMngEmail(),
            ];
        }
        return $this->json($response);
    }

    #[Route('/new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $request = $request->toArray();
        $managerObject = new Manager();
        $managerObject->setMngSurname($request["surname"])
            ->setMngName($request['name'])
            ->setMngMidlename($request["middlename"])
            ->setStationService($manager->getRepository(StationService::class)->findBy(["stn_address" => $request['station_service']])[0])
            ->setMngPassportDetails($request['passport_details'])
            ->setMngEmail($request['email'])
            ->setMngRole('manager');
        $password = $this->hasher->hashPassword($managerObject, $request['password']);
        $managerObject->setMngPassword($password);
        $manager->persist($managerObject);
        $manager->flush();
        unset($request['password']);
        return $this->json($request);
    }

    #[Route('/{mng_id}/edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, int $mng_id)
    {
        $request = $request->toArray();
        $managerObject = $manager->getRepository(Manager::class)->findBy(["mng_id" => $mng_id])[0];
        if ($managerObject) {
            $managerObject->setMngSurname($request["surname"])
                ->setMngName($request['name'])
                ->setMngMidlename($request["middlename"])
                ->setStationService($manager->getRepository(StationService::class)->findBy(["stn_address" => $request['station_service']])[0])
                ->setMngPassportDetails($request['passport_details']);
            $manager->persist($managerObject);
            $manager->flush();
            return $this->json($request);
        }
        return $this->json(["error" => "Такого менеджера не существует"]);
    }

    #[Route('/{mng_id}/show', methods: ['GET'])]
    public function show(EntityManagerInterface $manager, int $mng_id)
    {
        $mng = $manager->getRepository(Manager::class)->findOneBy(['mng_id' => $mng_id]);
        return $this->json([
            "id" => $mng->getMngId(),
            "surname" => $mng->getMngSurname(),
            "name" => $mng->getMngName(),
            "middlename" => $mng->getMngMidlename(),
            "station_service" => $mng->getStationService()->getStnAddress(),
            "passport_details" => $mng->getMngPassportDetails(),
            "role" => $mng->getMngRole() === 'admin' ? "Старший менеджер" : "Младший менеджер",
            "email" => $mng->getMngEmail(),
        ]);
    }

    #[Route('/{mng_id}/delete', methods: ["DELETE"])]
    public function delete(EntityManagerInterface $manager, int $mng_id)
    {
        $managerObject = $manager->getRepository(Manager::class)->findBy(["mng_id" => $mng_id])[0];
        if ($managerObject) {
            $manager->remove($managerObject);
            $manager->flush();
            return $this->json(["ok" => "Менеджер удален"]);
        }
        return $this->json(['error' => "Такого менеджера не существует"]);
    }

    #[Route('/auth', methods: ['POST'])]
    public function auth(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $mng = $manager->getRepository(Manager::class)->findOneBy(['mng_email' => $request['email']]);
        if ($mng) {
            if ($this->hasher->isPasswordValid($mng, $request['password'])) {
                $session = $this->requestStack->getSession();
                $session->set('role', $mng->getMngRole());
                $session->set('user_id', $mng->getId());
                return $this->json([
                    'id' => $session->get('user_id'),
                    'role' => $session->get('role')
                ]);
            }
        }
        return $this->json([
            'error'
        ]);
    }

    #[Route('/logout', methods: ['GET'])]
    public function logout(): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('role');
        $session->remove('user_id');
        return $this->json(['ok']);
    }

    #[Route('/report', methods:['GET'])]
    public function report(EntityManagerInterface $manager)
    {
        $response = $manager->getRepository(Manager::class)->report();
        return $this->json($response);
    }

    #[Route('/{mng_id}/bookings', methods:['GET'])]
    public function bookings(EntityManagerInterface $manager, int $mng_id)
    {
        $client = $manager->getRepository(Manager::class)->findOneBy(['mng_id' => $mng_id]);
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
                "client" => [
                    "id" => $booking->getClient()->getId(),
                    "surname" => $booking->getClient()->getCltSurname(),
                    "name" => $booking->getClient()->getCltName(),
                    "middlename" => $booking->getClient()->getCltMidlename(),
                    "email" => $booking->getClient()->getCltEmail(),
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
