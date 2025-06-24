<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TicketLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            // Ticket 1: appena aperto, mai assegnato
            [
                'ticket_id' => 1,
                'assegnato_a' => null,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => null,
                'chiuso_da' => null,
                'note_riapertura' => null,
                'note_chiusura' => null,
                'data_assegnazione' => null,
                'data_riapertura' => null,
                'data_chiusura' => null,
                "created_at" => Carbon::now()->subDays(2),
                "updated_at" => Carbon::now()->subDays(2),
            ],
            // Ticket 2: assegnato e in lavorazione
            [
                'ticket_id' => 2,
                'assegnato_a' => 1,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => null,
                'chiuso_da' => null,
                'note_riapertura' => null,
                'note_chiusura' => null,
                'data_assegnazione' => Carbon::now()->subDays(3),
                'data_riapertura' => null,
                'data_chiusura' => null,
                "created_at" => Carbon::now()->subDays(3),
                "updated_at" => Carbon::now()->subDays(3),
            ],
            // Ticket 3: stato = 1 ma riaperto, quindi ha un log precedente chiuso + uno attuale
            // Log chiuso
            [
                'ticket_id' => 3,
                'assegnato_a' => 2,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => null,
                'chiuso_da' => 2,
                'note_riapertura' => null,
                'note_chiusura' => 'Ticket chiuso con fix parziale.',
                'data_assegnazione' => Carbon::now()->subDays(6),
                'data_riapertura' => null,
                'data_chiusura' => Carbon::now()->subDays(5),
                "created_at" => Carbon::now()->subDays(6),
                "updated_at" => Carbon::now()->subDays(5),
            ],
            // Log attuale riapertura (non ancora assegnato)
            [
                'ticket_id' => 3,
                'assegnato_a' => null,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => 1,
                'chiuso_da' => null,
                'note_riapertura' => 'Problema non risolto completamente.',
                'note_chiusura' => null,
                'data_assegnazione' => null,
                'data_riapertura' => Carbon::now()->subDays(5),
                'data_chiusura' => null,
                "created_at" => Carbon::now()->subDays(5),
                "updated_at" => Carbon::now()->subDays(5),
            ],
            // Ticket 4: appena aperto
            [
                'ticket_id' => 4,
                'assegnato_a' => null,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => null,
                'chiuso_da' => null,
                'note_riapertura' => null,
                'note_chiusura' => null,
                'data_assegnazione' => null,
                'data_riapertura' => null,
                'data_chiusura' => null,
                "created_at" => Carbon::now()->subDays(1),
                "updated_at" => Carbon::now()->subDays(1),
            ],
            // Ticket 5: riaperto, quindi 2 log
            // Log chiuso
            [
                'ticket_id' => 5,
                'assegnato_a' => 3,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => null,
                'chiuso_da' => 1,
                'note_riapertura' => null,
                'note_chiusura' => 'Fix applicato ma utente ha chiesto verifica.',
                'data_assegnazione' => Carbon::now()->subDays(1),
                'data_riapertura' => null,
                'data_chiusura' => Carbon::now()->subHours(18),
                "created_at" => Carbon::now()->subDays(1),
                "updated_at" => Carbon::now()->subHours(18),
            ],
            // Log attuale riapertura (non assegnato)
            [
                'ticket_id' => 5,
                'assegnato_a' => null,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => 2,
                'chiuso_da' => null,
                'note_riapertura' => 'Richiesta riapertura per ulteriore verifica.',
                'note_chiusura' => null,
                'data_assegnazione' => null,
                'data_riapertura' => Carbon::now()->subHours(18),
                'data_chiusura' => null,
                "created_at" => Carbon::now()->subHours(18),
                "updated_at" => Carbon::now()->subHours(18),
            ],
            // Ticket 6: appena aperto, mai riaperto
            [
                'ticket_id' => 6,
                'assegnato_a' => null,
                'riaperto_da_user' => null,
                'riaperto_da_admin' => null,
                'chiuso_da' => null,
                'note_riapertura' => null,
                'note_chiusura' => null,
                'data_assegnazione' => null,
                'data_riapertura' => null,
                'data_chiusura' => null,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ];

        DB::table('ticket_logs')->insert($logs);
    }
}
