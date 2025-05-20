<?php

namespace Database\Seeders;

use App\Models\Technician;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(TechnicianSeeder::class);
        $this->call([
        StatusesTableSeeder::class,
    ]);
    }
}
