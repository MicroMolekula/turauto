<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/utils')]
class UtilsController extends AbstractController
{
    #[Route('/category_drv_lic', methods:['GET'])]
    public function index(): Response
    {
        return $this->json([
            "data" => [
                'B',
                'B1',
                'D1',
                'C',
                'M',
            ],
        ]);
    }

    #[Route('/gearboxes', methods:['GET'])]
    public function gearboxes(): Response
    {
        return $this->json([
            'АКПП',
            'МКПП',
            'РКППП',
        ]);
    }

    #[Route('/car_body_type', methods: ['GET'])]
    public function carBodyType(): Response
    {
        return $this->json([
            'Хэтчбек',
            'Седан',
            'Минивэн',
            'Внедорожник'
        ]);
    }
}
