<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BilleterieController extends AbstractController
{
    /**
     * @Route("/agenda", name="billeterie_agenda")
     */
    public function agenda(): Response
    {

        $agendaList =[];
        for($i = 0; $i < 9; $i++) {
            $row = [
                'Date' => '20/08/2021',
                'Time' => '16h-18h',
                'Artist' => 'artist n°' . $i,
                'Reservation' => 'Reserver une place'
            ];
            $agendaList[] = $row;
        }
        return $this->render('billeterie/agenda.html.twig', [
            'agendaList' => $agendaList
        ]);
    }    
}
