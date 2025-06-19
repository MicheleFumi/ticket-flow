<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $casts = [
        'data_assegnazione' => 'datetime',
        'data_riapertura' => 'datetime',
        'data_chiusura' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class, "assegnato_a");
    }

    public function allTechnicians()
    {
        return $this->belongsTo(Technician::class, "assegnato_a")->withoutGlobalScopes(["still_active"]);
    }

    public function assignToTechnician(Technician $technician, Ticket $ticket)
    {
        if (!$technician->is_available) {
            throw new \Exception("Il tecnico non è disponibile.");
        }

        $technician->is_available = false;
        $technician->save();

        $ticket->status_id = 2;
        $ticket->save();

        $this->assegnato_a = $technician->id;
        $this->data_assegnazione = Carbon::now();
        $this->save();

        return $this;
    }

    public function removeFromTechnician(Technician $technician, Ticket $ticket)
    {
        if ($this->assegnato_a !== $technician->id) {
            throw new \Exception("Il ticket non è assegnato a questo tecnico.");
        }

        $technician->is_available = true;
        $technician->save();

        if ($ticket->status_id === 2) {
            $ticket->status_id = 1;
            $this->assegnato_a = null;
            $this->data_assegnazione = null;
        }
        $ticket->save();
        $this->save();

        return $this;
    }

    public function close(Technician $technician, $note_chiusura, Ticket $ticket)
    {
        if ($ticket->status_id !== 2) {
            throw new \Exception("Il ticket non può essere chiuso perché non è assegnato.");
        }

        if ($this->status_id === 3) {
            throw new \Exception("Il ticket è già chiuso.");
        }

        if (!$technician->is_admin && $this->assegnato_a !== $technician->id) {
            throw new \Exception("Il ticket non è assegnato a questo tecnico.");
        }

        $assignedTechnician = $this->technician;
        if ($assignedTechnician) {
            $assignedTechnician->is_available = true;
            $assignedTechnician->save();
        }

        // $this->technician_id = null;
        $this->chiuso_da = $technician->id;
        $ticket->status_id = 3;
        $this->data_chiusura = Carbon::now();
        $this->note_chiusura = $note_chiusura;
        $this->save();
        $ticket->save();



        return $this;
    }

    public function closedBy()
    {
        return $this->belongsTo(Technician::class, 'chiuso_da');
    }
}
