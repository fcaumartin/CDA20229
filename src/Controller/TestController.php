<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(EntityManagerInterface $em): Response
    {
        ($c1 = new Client())
            ->setNom("client1")
            ->setPrenom("client1")
            ->setAdresse("client1");

        //$g1 = new Groupe();
        //$g1->setNom("groupe1");

        $repo = $em->getRepository(Groupe::class);
        $g1 = $repo->findOneBy(["nom" => "groupe1"]);

        // $c1->setGroupe($g1);
        $g1->addClient($c1);

        $em->persist($c1);
        // $em->persist($g1);

        $em->flush();

        return new Response("ok");
    }

    /**
     * @Route("/test2", name="test2")
     */
    public function index2(EntityManagerInterface $em): Response
    {
        

        $repo = $em->getRepository(Groupe::class);
        $g1 = $repo->findOneBy(["nom" => "groupe1"]);

        foreach ($g1->getClients() as $value) {
            $value->getNom();
        }

        dd($g1);

        return new Response("ok");
    }


    /**
     * @Route("/test3", name="test3")
     */
    public function index3(EntityManagerInterface $em): Response
    {
        

        $repo = $em->getRepository(Client::class);
        $clients = $repo->findAll();

        foreach ($clients as $value) {
            $value->getGroupe()->getNom();
        }

        dd($clients);

        return new Response("ok");
    }
}
