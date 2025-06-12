<?php

namespace App\Models;

use App\Notifications\ApiResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, CanResetPassword;

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'password',
        'telefono',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->where("is_deleted", false)->orderBy('created_at');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ApiResetPasswordNotification($token));
    }
}
