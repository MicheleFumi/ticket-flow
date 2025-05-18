<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Technician;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    /**
     * Esegui il seeder.
     */
    public function run(): void
    {
        Technician::create([
            'name' => 'Tecnico di Prova',
            'email' => 'tecnico@example.com',
            'password' => Hash::make('password123'), // Assicurati di cambiarlo in produzione
        ]);
    }
}
