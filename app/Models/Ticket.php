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
        // 'technician_id',
        // 'data_assegnazione',
        // 'data_chiusura',
        // 'chiuso_da',
        // 'note_chiusura',
        'is_reported',
        'commento_report',
        'reportato_da',
        'repot_date',
        'is_deleted',
        'is_reopened',
        // 'data_riapertura',
        // 'ragione_riapertura',
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

    public function allTechnicians()
    {
        return $this->belongsTo(Technician::class, "technician_id")->withoutGlobalScopes(["still_active"]);
    }

    public function images()
    {
        return $this->hasMany(TicketImage::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function latestLog()
    {
        return $this->hasOne(TicketLog::class)->latestOfMany();
    }

    public function reportatoDa()
    {
        return $this->belongsTo(Technician::class, 'reportato_da');
    }
}
