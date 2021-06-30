<?php

namespace App\Service;

class ArtistHandler
{
    /**
     * Renvoie l'agenda des concerts des artistes
     *
     * @param [array] $artists
     * @return array
     */
    public function handle(array $artists): array
    {
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
        return $agendaList;
    }
}