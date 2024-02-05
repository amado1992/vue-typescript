<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTDisponibilidadHabitacionesSeeder extends Seeder
{
  public function run()
  {
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 1,
      'causa_id' => 1,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 1,
      'causa_id' => 2,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 1,
      'causa_id' => 3,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 2,
      'causa_id' => 1,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 2,
      'causa_id' => 2,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 2,
      'causa_id' => 3,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 3,
      'causa_id' => 1,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 3,
      'causa_id' => 2,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtdisponibilidadhabitaciones')->insert([
      'cant_habitaciones_nd' => 0,
      'entidad_id' => 3,
      'causa_id' => 3,
      'clasificacion_id' => 1,
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtdisponibilidadhabitaciones');
  }
}
