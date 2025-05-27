<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'nome' => 'Marco',
                'cognome' => 'Bianchi',
                'email' => 'marco.bianchi@example.com',
                'password' => Hash::make('password123'),
                'telefono' => '3391234567',
                'is_technician' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Francesca',
                'cognome' => 'Rossi',
                'email' => 'francesca.rossi@example.com',
                'password' => Hash::make('password123'),
                'telefono' => '3287654321',
                'is_technician' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Luca',
                'cognome' => 'Ferrari',
                'email' => 'luca.ferrari@example.com',
                'password' => Hash::make('password123'),
                'telefono' => '3459876543',
                'is_technician' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Elena',
                'cognome' => 'Greco',
                'email' => 'elena.greco@example.com',
                'password' => Hash::make('password123'),
                'telefono' => '3298765432',
                'is_technician' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nome' => 'Rick',
                'cognome' => 'Roll',
                'email' => 'rick.roll@example.com',
                'password' => Hash::make('password123'),
                'telefono' => '3312345678',
                'is_technician' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
