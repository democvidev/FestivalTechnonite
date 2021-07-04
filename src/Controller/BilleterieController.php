<?php

namespace App\Controller;

use App\Service\ArtistHandler;
use App\Form\BilleterieFormType;
use App\Service\BilletterieHandler;
use App\Repository\ArtistRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BilleterieController extends AbstractController
{
    private $festivalName = 'Festival Technonite Demande de Reservation';

    private $adminEmail = 'festival_reservation@festival.com';

    /**
     * tableau avec les dates des concerts
     */
    private $dates = ['20/08/2021', '21/08/2021', '22/08/2021'];

    /**
     * tableau avec les plages horaires de chaque journée
     */
    private $hours = ['16h-18h', '18h-20h', '21h-23h'];

    /**
     * stocke l'instance de l'objet au moment de sa création
     */
    private $artistRepository;

    /**
     * la méthode magique est appelée lors de l'instanciation de l'objet correspondant
     */
    public function __construct(ArtistRepository $artistRepository)
    {
        $this->artistRepository = $artistRepository;
    }

    /**
     * @Route("/agenda", name="billeterie_agenda")
     */
    public function showAgenda(ArtistRepository $artistRepository, ArtistHandler $artistHandler): Response
    {
        $artists = $artistRepository->findArtitsInConcert(); // les artistes de tous les 9 concerts dans l'ordre ASC
        $agendaList = $artistHandler->handle($artists, $this->dates, $this->hours); // le service retourne l'agenda des concerts        
        return $this->render('billeterie/agenda.html.twig', [
            'agendaList' => $agendaList,
        ]);
    }

    /**
     * @Route("/billeterie", name="billeterie_reserve")
     */
    public function reservePlaceByMail(Request $request, BilletterieHandler $billetterieHandler, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(BilleterieFormType::class);
        $artists = $this->artistRepository->findArtitsInConcert();

        $i = 0;
        foreach ($this->dates as $date) {
            foreach ($this->hours as $hour) {
                $form->add(
                    'nbTickets_' . str_replace(" ", "", $artists[$i]->getSlug()),
                    IntegerType::class,
                    [
                        'label' => $artists[$i]->getName() . ' - ' . $date . ' - ' . $hour,
                        'attr' => ($request->get('nbPlace') && ($request->get('artist') == $artists[$i]->getName())) ? ['value' => $request->get('nbPlace'), 'min' => '0'] : ['value' => '0', 'min' => '0'],
                    ]
                );
                $i++;
            }
        }

        $form->add('Envoyer', SubmitType::class, [
            'attr' => [
                'class' => 'mb-3 mt-3 btn btn-lg btn-primary'
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data += ['email' => $this->getUser()->getEmail()];

            // enleve les clés avec les valeurs à 0
            $dataFormFiltered = [];
            foreach ($data as $key => $value) {
                if ($value != '0') {
                    $dataFormFiltered[$key] = $value;
                }
            }

            $emailBody = $this->renderView('billeterie/email.html.twig', [
                'data' => $dataFormFiltered,
            ]);

            $message = $billetterieHandler->handle($this->festivalName, $this->adminEmail, $emailBody, $this->getUser()->getEmail());

            $mailer->send($message);

            $this->addFlash('success', 'Votre réservation a bien été envoyée !');
            return $this->redirectToRoute('home_index');
        }

        return $this->render('billeterie/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
