<?php

namespace App\Controller;

use App\Form\BilleterieFormType;
use App\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class BilleterieController extends AbstractController
{
    /**
     * @Route("/agenda", name="billeterie_agenda")
     */
    public function agenda(ArtistRepository $artistRepository): Response
    {
        $artists = $artistRepository->findByConcert(); // les artistes de tous les 9 concerts dans l'ordre ASC
        $agendaList = []; // on va stocker les données de chaque artiste et son concert        
        $date = ['20/08/2021', '21/08/2021', '22/08/2021']; // tableau avec les dates des concerts
        $hour = ['16h-18h', '18h-20h', '21h-23h']; // tableau avec les plages horaires de chaque journée
        $concert = 0; // compteur des concerts de 0 à 8
        for ($i = 0; $i < count($date); $i++) {
            for ($j = 0; $j < count($hour); $j++) {
                $row = [
                    'Date' => $date[$i],
                    'Time' => $hour[$j],
                    'Artist' => $artists[$concert],
                    'Reservation' => 'Reserver une place',
                ];
                $concert++;
                $agendaList[] = $row;
            }
        }
        // dd($agendaList);
        return $this->render('billeterie/agenda.html.twig', [
            'agendaList' => $agendaList,
        ]);
    }

    /**
     * @Route("/billeterie", name="billeterie_form")
     */
    public function form(
        Request $request,
        \Swift_Mailer $mailer,
        UserInterface $user
    ): Response {
        $form = $this->createForm(BilleterieFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data += ['email' => $user->getEmail()];
            // dd($data);

            //fabriquation du mail
            $message = (new \Swift_Message('Nouvelle Réservation'))
                //hydratation d'email
                ->setFrom($user->getEmail())
                ->setTo('admin@admin.com')
                ->setBody(
                    $this->renderView('billeterie/email.html.twig', [
                        'data' => $data,
                    ]),
                    'text/html'
                );
            //envoie du message
            $mailer->send($message);

            $this->addFlash(
                'success',
                'Votre réservation a bien été envoyée !'
            );
            return $this->redirectToRoute('home_index');
        }

        return $this->render('billeterie/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
