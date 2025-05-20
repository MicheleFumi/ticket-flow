<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $fillable = [
        'user_id',
        'titolo',
        'commento',
        'stato',
    ];


    public function statuses()
{
    return $this->belongsToMany(Status::class);
}
}
