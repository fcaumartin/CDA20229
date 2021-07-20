<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Groupe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

        // foreach ($clients as $value) {
        //     $value->getGroupe()->getNom();
        // }

        // dd($clients);

        return $this->render("test/test3.html.twig", [
            "clients" => $clients
        ]);
    }

    /**
     * @Route("/test4", name="test4")
     */
    public function index4(EntityManagerInterface $em, Request $request): Response
    {
        
        $ville = $request->query->get("ville");
        $repo = $em->getRepository(Client::class);
        $clients = $repo->findAllByAdresse($ville);
        // $clients = $repo->findBy([ "adresse" => $ville]);

        dd($clients);

        return $this->render("test/test3.html.twig", [
            "clients" => $clients
        ]);
    }

    /**
     * @Route("/test5", name="test5")
     */
    public function index5(EntityManagerInterface $em, Request $request): Response
    {
        if ("POST" === $request->getMethod()) {
            $data = $request->request->all();
            //dd($data);

            $cli = new Client();
            $cli->setNom($data["nom"]);
            $cli->setPrenom($data["prenom"]);
            $cli->setAdresse("");

            $em->persist($cli);
            $em->flush();

            $photo = $request->files->get("photo");
            //dd($photo);
            //$this->getParameter("%kernel.project_dir%");
            $photo->move("uploads", $cli->getId() . ".png");


            return $this->redirectToRoute("test3");
        }
        

        return $this->render("test/test5.html.twig", [
            
        ]);
    }
}
