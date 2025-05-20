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
        
    ];


    public function status()
{
    return $this->belongsTo(Status::class);
}
}
