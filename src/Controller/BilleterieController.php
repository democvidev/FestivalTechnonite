<?php

namespace App\Controller;

use App\Form\BilleterieFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

     /**
     * @Route("/billeterie", name="billeterie_form")
     */
    public function form(): Response
    {
        $form = $this->createForm(BilleterieFormType::class);
       
        
        return $this->render('billeterie/form.html.twig', [
            'form' => $form->createView()
        ]);
    }    
}
