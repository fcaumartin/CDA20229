<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api_test/clients", name="api_client")
     */
    public function index(ClientRepository $repo): Response
    {
        $clients = $repo->findAll();
        $context = [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        
        ];

        return $this->json($clients, 200, [], $context);
    }
}
