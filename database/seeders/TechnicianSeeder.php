<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Technician;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    public function run(): void
    {
        Technician::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'email' => env("SUPERADMIN_EMAIL"),
            'password' => Hash::make(env("SUPERADMIN_PASSWORD")),
            'telefono' => '1234567890',
            'is_admin' => true,
            "is_superadmin" => true,
            'is_available' => true,
        ]);
    }
}
