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
        $this->technician_id = $technician->id;
        $this->data_assegnazione = Carbon::now();
        $this->save();

        return $this;
    }
}
