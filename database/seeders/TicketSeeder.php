<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{


    public function run()
    {

        $tickets = [
            [
                'user_id' => 1,
                'titolo' => 'Problema login',
                'commento' => 'Non riesco ad accedere al sistema.',
                'status_id' => 1,
                'technician_id' => null,
                'data_assegnazione' => null,
                'data_chiusura' => null,
                'chiuso_da' => null,
                'note_chiusura' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_reported' => 0,
                'commento_report' => ''
            ],
            [
                'user_id' => 2,
                'titolo' => 'Errore pagina dashboard',
                'commento' => 'La dashboard mostra errori casuali.',
                'status_id' => 2,
                'technician_id' => 1,
                'data_assegnazione' => Carbon::now()->subDays(2),
                'data_chiusura' => null,
                'chiuso_da' => null,
                'note_chiusura' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_reported' => 0,
                'commento_report' => ''
            ],
            [
                'user_id' => 3,
                'titolo' => 'Aggiornamento dati utente',
                'commento' => 'Non riesco a modificare i miei dati profilo.',
                'status_id' => 1,
                'technician_id' => null,
                'data_assegnazione' => null,
                'data_chiusura' => null,
                'chiuso_da' => null,
                'note_chiusura' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_reported' => 0,
                'commento_report' => ''
            ],
            [
                'user_id' => 4,
                'titolo' => 'Bug nella funzione di ricerca',
                'commento' => 'La ricerca non restituisce risultati corretti.',
                'status_id' => 1,
                'technician_id' => null,
                'data_assegnazione' => null,
                'data_chiusura' => null,
                'chiuso_da' => null,
                'note_chiusura' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_reported' => 0,
                'commento_report' => ''
            ],
            [
                'user_id' => 5,
                'titolo' => 'Crash applicazione',
                'commento' => 'L’applicazione si chiude improvvisamente.',
                'status_id' => 3,
                'technician_id' => 1,
                'data_assegnazione' => Carbon::now()->subDays(7),
                'data_chiusura' => Carbon::now()->subDays(1),
                'chiuso_da' => 1,
                'note_chiusura' => 'Fix applicato con aggiornamento patch 1.2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_reported' => 1,
                'commento_report' => 'il ticket era stato aperto due volte, nonostante avessi già spiegato che il problema è della rete interna alla sede.'
            ],
            [
                'user_id' => 1,
                'titolo' => 'Mancata notifica email',
                'commento' => 'Non ricevo le notifiche email dal sistema.',
                'status_id' => 3,
                'technician_id' => 1,
                'data_assegnazione' => Carbon::now()->subDays(8),
                'data_chiusura' => Carbon::now()->subDays(2),
                'chiuso_da' => 1,
                'note_chiusura' => 'Configurato correttamente il server SMTP',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'is_reported' => 0,
                'commento_report' => ''
            ],
        ];

        foreach ($tickets as $ticket) {
            $ticketId = DB::table('tickets')->insertGetId($ticket);

            // Inserisco 2 immagini per ogni ticket
            DB::table('ticket_images')->insert([
                [
                    'ticket_id' => $ticketId,
                    'file_path' => 'https://placehold.co/400',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'ticket_id' => $ticketId,
                    'file_path' => 'https://placehold.co/400?text=Second+Image',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
