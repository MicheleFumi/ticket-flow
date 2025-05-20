<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
     protected $fillable = [
        'status'
    ];

public function tickets()
{
    return $this->belongsToMany(Ticket::class);
}


}
