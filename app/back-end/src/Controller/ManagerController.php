<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Entity\StationService;
use App\Repository\ManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manager')]
class ManagerController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
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
                "role" => $manager->getMngRole() === 'admin' ? "Старший менеджер" : "Младший менеджер",
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

    #[Route('/{mng_id}/delete', methods:["DELETE"])]
    public function delete(EntityManagerInterface $manager, int $mng_id)
    {
        $managerObject = $manager->getRepository(Manager::class)->findBy(["mng_id" => $mng_id])[0];
        if($managerObject){
            $manager->remove($managerObject);
            $manager->flush();
            return $this->json(["ok" => "Менеджер удален"]);
        }
        return $this->json(['error' => "Такого менеджера не существует"]);
    }
}
