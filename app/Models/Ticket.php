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
}
