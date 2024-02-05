<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTTemperaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttemperatura')->insert([
          'nombre'=> 'Climatizado',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mttemperatura')->insert([
          'nombre'=> 'Frigorífico',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mttemperatura')->insert([
          'nombre'=> 'Convencional a temperatura ambiente',
          'created_at' => now(),
          'updated_at' => now()
        ]);
    }
}
