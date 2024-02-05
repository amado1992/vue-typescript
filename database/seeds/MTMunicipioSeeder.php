<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTMunicipioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('mtmunicipio')->insert([
      'nombre' => 'Plaza',
      'provincia_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmunicipio')->insert([
      'nombre' => '10 de Octubre',
      'provincia_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmunicipio')->insert([
      'nombre' => 'San Miguel',
      'provincia_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmunicipio')->insert([
      'nombre' => 'Playa',
      'provincia_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmunicipio')->insert([
      'nombre' => 'Cruces',
      'provincia_id' => '4',
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmunicipio')->insert([
      'nombre' => 'Cumanayagua',
      'provincia_id' => '4',
      'created_at' => Carbon::now()
    ]);
    DB::table('mtmunicipio')->insert([
      'nombre' => 'Palmira',
      'provincia_id' => '4',
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtmunicipio');
  }
}
