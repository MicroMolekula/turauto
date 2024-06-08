<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class Test2Controller extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        // Получение доступа к сессии в конструкторе *НЕ* рекомендуется, так как
        // к ней еще может не быть доступа или это может привести к нежелательным побочным эффектам
        // $this->session = $requestStack->getSession();
    }

    #[Route('/test2', methods:['GET'])]
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        return $this->json($session);
    }
}
