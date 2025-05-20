<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{ DB::table('statuses')->insert([
            [
                'titolo' => 'Aperto',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titolo' => 'In Lavorazione',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titolo' => 'Chiuso',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);


}



}