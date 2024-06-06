<?php

namespace App\Controller;

use App\Repository\CarClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/car_class')]
class CarClassController extends AbstractController
{
    #[Route('/', methods:['GET'])]
    public function index(CarClassRepository $carClassRepository): Response
    {

        $carClasses = $carClassRepository->findAll();
        $response = [];
        foreach($carClasses as $carClass){
            $response[] = [
                'title' => $carClass->getClsTitle(),
            ];
        }
        return $this->json($response);
    }
}
