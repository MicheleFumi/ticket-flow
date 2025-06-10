<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'titolo',
        'commento',
        'status_id',
        'technician_id',
        'data_assegnazione',
        'data_chiusura',
        'chiuso_da',
        'note_chiusura',
        'is_reported',
        'commento_report',
        'reportato_da',
        'repot_date'
    ];

    protected $casts = [
        'data_assegnazione' => 'datetime',
        'data_chiusura' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function images()
    {
        return $this->hasMany(TicketImage::class);
    }
    public function reportatoDa()
    {
        return $this->belongsTo(Technician::class, 'reportato_da');
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

        $this->technician_id = null;
        $this->status_id = 1;
        $this->data_assegnazione = null;
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
