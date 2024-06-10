<?php

namespace App\Controller;

use App\Entity\AddService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/add_service')]
class AddServiceController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(EntityManagerInterface $manager): Response
    {
        $addServices = $manager->getRepository(AddService::class)->findAll();
        $response = [];

        foreach ($addServices as $addService)
        {
            $response[] = [
                'title' => $addService->getSrvType(),
                'cost' => $addService->getSrvCost(),
            ];
        }
        return $this->json($response);
    }

    #[Route('/new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $addService = new AddService();
        $addService->setSrvCost($request['cost'])
                   ->setSrvType($request['title']);
        $manager->persist($addService);
        $manager->flush();
        return $this->json(['status' => 'ok']);
    }

    #[Route('/{srv_type}/edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, string $srv_type): Response
    {
        $request = $request->toArray();
        $addService = $manager->getRepository(AddService::class)->findOneBy(['srv_type' => $srv_type]);
        if($manager){
            $addService->setSrvCost($request['cost']);
            $manager->persist($addService);
            $manager->flush();
            return $this->json(['status' => 'ok']);
        }
        return $this->json(['status' => 'error']);
    }

    #[Route('/{srv_type}/delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $manager, string $srv_type)
    {
        $addService = $manager->getRepository(AddService::class)->findOneBy(['srv_type' => $srv_type]);
        if($addService){
            $manager->remove($addService);
            $manager->flush();
            return $this->json(['status' => 'ok']);
        }
        return $this->json(['status'=>'error']);
    }
}
