<?php

namespace App\Controller;

use App\Service\ArtistHandler;
use App\Form\BilleterieFormType;
use App\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BilleterieController extends AbstractController
{

    private $nbConcerts = 9;

    /**
     * @Route("/agenda", name="billeterie_agenda")
     */
    public function agenda(ArtistRepository $artistRepository, ArtistHandler $artistHandler): Response
    {
        $artists = $artistRepository->findByConcert($this->nbConcerts); // les artistes de tous les 9 concerts dans l'ordre ASC
        $agendaList = $artistHandler->handle($artists); // le service retourne l'agenda des concerts        
        return $this->render('billeterie/agenda.html.twig', [
            'agendaList' => $agendaList,
        ]);
    }

    /**
     * @Route("/billeterie", name="billeterie_form")
     */
    public function form(Request $request, \Swift_Mailer $mailer): Response 
    {
        $form = $this->createForm(BilleterieFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data += ['email' => $this->getUser()->getEmail()];

            //fabriquation du mail
            $message = (new \Swift_Message('Nouvelle Réservation'))
                //hydratation d'email
                ->setFrom($this->getUser()->getEmail())
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
        } else{            
            // préremplissage du formulaire
            if($request->get('date') != null){
                $startDay = \DateTime::createFromFormat('d/m/Y', $request->get('date'));
                $form->get('date')->setData($startDay);
            }
    
            if($request->get('time') != null){
                $form->get('hour')->setData($request->get('time'));
            }
    
            if($request->get('artist') != null){
                $form->get('artist')->setData($request->get('artist'));
            }
        }

        return $this->render('billeterie/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
