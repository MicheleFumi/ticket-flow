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

    ];

    protected $casts = [
        'data_assegnazione' => 'datetime',
        'data_chiusura' => 'datetime',
    ];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
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

    public function close()
    {
        $this->status_id = 3; // Assuming 3 is the ID for "Closed"
        $this->data_chiusura = Carbon::now();
        $this->save();

        return $this;
    }
}
