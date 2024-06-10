<?php

namespace App\Controller;

use App\Entity\CarClass;
use App\Repository\CarClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
                'coef' => $carClass->getClsCoefCost(),
                'drv_exp' => $carClass->getClsDrvExp(),
                'category' => $carClass->getClsCategory(),
                'mileage_limit' => $carClass->getClsMileageLimit(),
            ];
        }
        return $this->json($response);
    }

    #[Route('/new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $manager)
    {
        $request = $request->toArray();
        $carClass = new CarClass();
        $carClass->setClsTitle($request['title'])
                 ->setClsCoefCost($request['coef'])
                 ->setClsCategory($request['category'])
                 ->setClsDrvExp($request['drv_exp'])
                 ->setClsMileageLimit($request['mileage_limit']);
        $manager->persist($carClass);
        $manager->flush();
        return $this->json([$request]);
    }

    #[Route('/{cls_title}/edit', methods:['PUT', 'PATCH'])]
    public function edit(Request $request, EntityManagerInterface $manager, string $cls_title)
    {
        $request = $request->toArray();
        $carClass = $manager->getRepository(CarClass::class)->findOneBy(['cls_title' => $cls_title]);
        if($carClass){
            $carClass->setClsCoefCost($request['coef'])
                ->setClsCategory($request['category'])
                ->setClsDrvExp($request['drv_exp'])
                ->setClsMileageLimit($request['mileage_limit']);
            $manager->persist($carClass);
            $manager->flush();
            return $this->json(['status' => 'ok']);
        }
        return $this->json(['status' => 'error']);
    }

    #[Route('/{cls_title}/delete', methods:['DELETE'])]
    public function delete(EntityManagerInterface $manager, string $cls_title)
    {
        $carClass = $manager->getRepository(CarClass::class)->findOneBy(['cls_title' => $cls_title]);
        if($carClass) {
            $manager->remove($carClass);
            $manager->flush();
            return $this->json(['status'=>'ok']);
        }
        return $this->json(['status' => 'error']);
    }
}
