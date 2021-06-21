<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    // /**
    //  * @Route("/agenda", name="home_agenda")
    //  */
    // public function agenda(): Response
    // {
    //     return $this->render('home/agenda.html.twig', []);
    // }

    // /**
    //  * @Route("/billeterie", name="home_billeterie")
    //  */
    // public function billeterie(): Response
    // {
    //     return $this->render('home/billeterie.html.twig', []);
    // }

}
