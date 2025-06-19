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
        return $this->belongsTo(Technician::class);
    }

    public function allTechnicians()
    {
        return $this->belongsTo(Technician::class, "technician_id")->withoutGlobalScopes(["still_active"]);
    }

    public function assignToTechnician(Technician $technician)
    {
        if (!$technician->is_available) {
            throw new \Exception("Il tecnico non è disponibile.");
        }

        $technician->is_available = false;
        $technician->save();

        $this->technician_id = $technician->id;
        $this->status_id = 2;
        $this->data_assegnazione = Carbon::now();
        $this->save();

        return $this;
    }

    public function removeFromTechnician(Technician $technician)
    {
        if ($this->technician_id !== $technician->id) {
            throw new \Exception("Il ticket non è assegnato a questo tecnico.");
        }

        $technician->is_available = true;
        $technician->save();

        if ($this->status_id === 2) {
            $this->status_id = 1;
            $this->technician_id = null;
            $this->data_assegnazione = null;
        }
        $this->save();

        return $this;
    }

    public function close(Technician $technician, $note_chiusura)
    {
        if ($this->status_id !== 2) {
            throw new \Exception("Il ticket non può essere chiuso perché non è assegnato.");
        }

        if ($this->status_id === 3) {
            throw new \Exception("Il ticket è già chiuso.");
        }

        if (!$technician->is_admin && $this->technician_id !== $technician->id) {
            throw new \Exception("Il ticket non è assegnato a questo tecnico.");
        }

        $assignedTechnician = $this->technician;
        if ($assignedTechnician) {
            $assignedTechnician->is_available = true;
            $assignedTechnician->save();
        }

        // $this->technician_id = null;
        $this->chiuso_da = $technician->id;
        $this->status_id = 3;
        $this->data_chiusura = Carbon::now();
        $this->note_chiusura = $note_chiusura;
        $this->save();



        return $this;
    }

    public function closedBy()
    {
        return $this->belongsTo(Technician::class, 'chiuso_da');
    }
}
