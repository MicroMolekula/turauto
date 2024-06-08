<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Manager;
use App\Entity\Client;

class AuthController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    private RequestStack $requestStack;

    public function __construct(UserPasswordHasherInterface $hasher, RequestStack $requestStack)
    {
        $this->hasher = $hasher;
        $this->requestStack = $requestStack;
    }

    #[Route('/auth', methods:['POST'])]
    public function auth(Request $request, EntityManagerInterface $manager): Response
    {
        $request = $request->toArray();
        $mng = $manager->getRepository(Manager::class)->findOneBy(['mng_email' => $request['email']]);
        $client = $manager->getRepository(Client::class)->findOneBy(['clt_email' => $request['email']]);
        if($mng){
            if($this->hasher->isPasswordValid($mng, $request['password'])){
                $session = $this->requestStack->getSession();
                $session->set('role', $mng->getMngRole());
                $session->set('user_id', $mng->getId());
                return $this->json([
                    'status' => 'ok',
                    'id' => $session->get('user_id'),
                    'role' => $session->get('role')
                ]);
            }
        }
        if($client){
            if($this->hasher->isPasswordValid($client, $request['password'])){
                $session = $this->requestStack->getSession();
                $session->set('role', 'client');
                $session->set('user_id', $client->getId());
                return $this->json([
                    'status' => 'ok',
                    'id' => $session->get('user_id'),
                    'role' => $session->get('role')
                ]);
            }
        }
        return $this->json([
            'status' => 'error'
        ]);
    }

    #[Route('/logout', methods:['GET'])]
    public function logout(): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('role');
        $session->remove('user_id');
        return $this->json(['ok']);
    }
}
