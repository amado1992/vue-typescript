<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtactividad')->insert([
          'nombre'=> 'Productos Alimenticios',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mtactividad')->insert([
          'nombre'=> 'Productos No Alimenticios',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mtactividad')->insert([
          'nombre'=> 'Mixto',
          'created_at' => now(),
          'updated_at' => now()
        ]);
    }
}
