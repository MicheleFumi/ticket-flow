<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    protected $fillable = [
        'ticket_id',
        'assegnato_a',
        'riaperto_da_user',
        'riaperto_da_admin',
        'chiuso_da',
        'note_riapertura',
        'note_chiusura',
        'data_assegnazione',
        'data_riapertura',
        'data_chiusura',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
