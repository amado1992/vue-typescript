<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTVehiculoTransmisionSeeder extends Seeder
{
  public function run()
  {
    DB::table('mtvehiculo_transmisions')->insert([
      'codigo' => 'KXW1',
      'vehiculo' => 'Agua',
      'created_at' => Carbon::now()
    ]);
    DB::table('mtvehiculo_transmisions')->insert([
      'codigo' => 'KJC2',
      'vehiculo' => 'Objetos',
      'created_at' => Carbon::now()
    ]);
    DB::table('mtvehiculo_transmisions')->insert([
      'codigo' => 'YFX3',
      'vehiculo' => 'Alimentos',
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtvehiculo_transmisions');
  }

}
