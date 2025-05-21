<?php

namespace App\Models;

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


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
