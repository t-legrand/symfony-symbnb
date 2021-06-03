<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo) {

        return $this->render(
            'home.html.twig',
            [
                'ads' =>$adRepo->findBestAds(3),
                'users' => $userRepo->findBestUsers()
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