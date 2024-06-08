<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class TestController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        // Получение доступа к сессии в конструкторе *НЕ* рекомендуется, так как
        // к ней еще может не быть доступа или это может привести к нежелательным побочным эффектам
        // $this->session = $requestStack->getSession();
    }

    #[Route('/test', methods:['POST'])]
    public function index(Request $request): Response
    {
        $request = $request->toArray();
        $session = $this->requestStack->getSession();
        $session->set('data', $request['data']);
        return $this->json(['data' => $session->get('data')]);
    }

    #[Route('/test_ses', methods:['GET'])]
    public function test():Response
    {
        $session = $this->requestStack->getSession();
        return $this->json(['data' => $session->get('data')]);
    }
}
