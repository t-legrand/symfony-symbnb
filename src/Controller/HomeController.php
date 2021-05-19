<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    /**
     * @Route("/", name="homepage")
     */
    public function home() {
        $prenoms = ["Lior" => 31, "Joseph" => 12, "Marie" => 55];

        return $this->render('home.html.twig',
        [
            'title' => "Bonjour",
            'age' => 12,
            'tableau' => $prenoms
        ]);
    }

    /**
     * Montre la page qui dit bonjour
     * 
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/hello/", name="hello_base")
     * @Route("/hello/{prenom}/{age}", name="hello_prenom")
     */
    public function hello($prenom = "anonyme", $age = 0) {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
            );
    }
}