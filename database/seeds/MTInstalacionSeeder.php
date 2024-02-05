<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTInstalacionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('mtinstalacion')->insert([
      'activo' => '1',
      'nombre' => 'Grupo de Electronica para el Turismo',
      'codigo' => 'GET',
      'provincia_id' => '1',
      'osde_id' => '1',
      'complejo' => 'complejo',
      'tipoInst_id' => '1',
      'categoria_id' => '1',
      'cadHotelera_id' => '1',
      'tomo' => '123',
      'folio' => '456',
      'registro' => '789',
      'fecha_registro' => Carbon::now(),
      'direccion' => 'asdfrg gtyyhg',
      'correo_electronico' => 'aqweed@dfgttt.loj',
      'telefono' => '78308904',
      'created_at' => Carbon::now()

    ]);
    DB::table('mtinstalacion')->insert([
      'activo' => '1',
      'nombre' => 'Instalacion02',
      'codigo' => 'Inst-02',
      'provincia_id' => '1',
      'osde_id' => '1',
      'complejo' => 'complejo',
      'tipoInst_id' => '2',
      'categoria_id' => '1',
      'cadHotelera_id' => '1',
      'tomo' => '123',
      'folio' => '456',
      'registro' => '789',
      'fecha_registro' => Carbon::now(),
      'direccion' => 'asdfrg gtyyhg',
      'correo_electronico' => 'aqweed@dfgttt.loj',
      'telefono' => '78308904',
      'created_at' => Carbon::now()
    ]);
    DB::table('mtinstalacion')->insert([
      'activo' => '1',
      'nombre' => 'Instalacion03',
      'codigo' => 'Inst-03',
      'provincia_id' => '1',
      'osde_id' => '1',
      'complejo' => 'complejo',
      'tipoInst_id' => '2',
      'categoria_id' => '1',
      'cadHotelera_id' => '1',
      'tomo' => '123',
      'folio' => '456',
      'registro' => '789',
      'fecha_registro' => Carbon::now(),
      'direccion' => 'asdfrg gtyyhg',
      'correo_electronico' => 'aqweed@dfgttt.loj',
      'telefono' => '78308904',
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtinstalacion');
  }
}
