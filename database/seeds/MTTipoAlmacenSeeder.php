<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoAlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipoalmacen')->insert([
          'nombre'=> 'Techado',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mttipoalmacen')->insert([
          'nombre'=> 'A Cielo Abierto',
          'created_at' => now(),
          'updated_at' => now()
        ]);
    }
}
