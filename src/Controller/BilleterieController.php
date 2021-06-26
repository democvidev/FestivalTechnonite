<?php

namespace App\Controller;

use App\Form\BilleterieFormType;
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
    public function agenda(): Response
    {
        $agendaList = [];
        for ($i = 0; $i < 9; $i++) {
            $row = [
                'Date' => '20/08/2021',
                'Time' => '16h-18h',
                'Artist' => 'artist n°' . $i,
                'Reservation' => 'Reserver une place',
            ];
            $agendaList[] = $row;
        }
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
            $data += ["email" => $user->getEmail()];
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
