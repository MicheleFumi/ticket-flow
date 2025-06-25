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
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
                'is_reported' => 0,
                'is_deleted' => 0,
                'is_reopened' => 0,
                'commento_report' => ''
            ],
            [
                'user_id' => 2,
                'titolo' => 'Errore nella dashboard',
                'commento' => 'Vedo numeri sballati nella sezione vendite.',
                'status_id' => 2,
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(3),
                'is_reported' => 1,
                'is_deleted' => 0,
                'is_reopened' => 0,
                'commento_report' => 'Discrepanze nei dati mostrati.'
            ],
            [
                'user_id' => 3,
                'titolo' => 'Richiesta nuova funzionalità',
                'commento' => 'Sarebbe utile avere un filtro per data.',
                'status_id' => 1,
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(5),
                'is_reported' => 0,
                'is_deleted' => 0,
                'is_reopened' => 1,
                'commento_report' => ''
            ],
            [
                'user_id' => 4,
                'titolo' => 'Sistema lento',
                'commento' => 'Il caricamento delle pagine è molto lento oggi.',
                'status_id' => 1,
                'created_at' => Carbon::now()->subHours(10),
                'updated_at' => Carbon::now()->subHours(5),
                'is_reported' => 1,
                'is_deleted' => 0,
                'is_reopened' => 0,
                'commento_report' => 'Problemi di performance notati da più utenti.'
            ],
            [
                'user_id' => 1,
                'titolo' => 'Bug nel form di contatto',
                'commento' => 'Il bottone di invio non fa nulla.',
                'status_id' => 1,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now(),
                'is_reported' => 0,
                'is_deleted' => 0,
                'is_reopened' => 1,
                'commento_report' => ''
            ],
            [
                'user_id' => 5,
                'titolo' => 'Errore 500 dopo login',
                'commento' => 'Appena faccio login vedo una schermata bianca con errore 500.',
                'status_id' => 1,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHour(),
                'is_reported' => 1,
                'is_deleted' => 0,
                'is_reopened' => 0,
                'commento_report' => 'Errore server dopo autenticazione.'
            ],
        ];


        foreach ($tickets as $ticket) {
            $ticketId = DB::table('tickets')->insertGetId($ticket);


            DB::table('ticket_images')->insert([
                [
                    'ticket_id' => $ticketId,
                    'file_path' => 'https://placehold.co/400',
                    'file_name' => 'placeholder_image_1.png',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'ticket_id' => $ticketId,
                    'file_path' => 'https://placehold.co/400?text=Second+Image',
                    'file_name' => 'placeholder_image_2.png',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
