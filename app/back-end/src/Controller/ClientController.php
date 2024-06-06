<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', methods:['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        $response = [];
        foreach($clients as $client){
            if($client->getDeletedAt() == null){
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

    #[Route('/{clt_id}/edit', methods:['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, int $clt_id): Response
    {
        $request = $request->toArray();
        $client = $manager->getRepository(Client::class)->findBy(["clt_id" => $clt_id])[0];
        if($client){
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

    #[Route('/{clt_id}/delete', methods:['DELETE'])]
    public function delete(EntityManagerInterface $manager, int $clt_id): Response
    {
        $client = $manager->getRepository(Client::class)->findBy(["clt_id" => $clt_id])[0];
        if($client){
            $dateTime = date('Y-m-d H:i:s');
            $client->setDeletedAt(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dateTime));
            $manager->persist($client);
            $manager->flush();
            return $this->json(["ok" => "Клиент удален"]);
        }
        return $this->json(["error" => "Такого клиента не существует"]);
    }
}
