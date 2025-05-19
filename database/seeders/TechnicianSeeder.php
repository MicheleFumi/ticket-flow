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
            'email' => 'tecnico@example.com',
            'password' => Hash::make('password123'),
            'telefono' => '1234567890',
            'is_admin' => true,
            'is_avaible' => true,
        ]);
    }
}
