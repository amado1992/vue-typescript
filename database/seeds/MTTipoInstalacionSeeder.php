<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoInstalacionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('mttipoinst')->insert([
      'activo' => '0',
      'nombre' => 'Hotel',
      'created_at' => Carbon::now()
    ]);
    DB::table('mttipoinst')->insert([
      'activo' => '1',
      'nombre' => 'Aparthotel',
      'created_at' => Carbon::now()
    ]);

    DB::table('mttipoinst')->insert([
      'activo' => '1',
      'nombre' => 'Motel',
      'created_at' => Carbon::now()
    ]);

    DB::table('mttipoinst')->insert([
      'activo' => '1',
      'nombre' => 'Villa',
      'created_at' => Carbon::now()
    ]);

    DB::table('mttipoinst')->insert([
      'activo' => '1',
      'nombre' => 'Base de campismo',
      'created_at' => Carbon::now()
    ]);

    DB::table('mttipoinst')->insert([
      'activo' => '1',
      'nombre' => 'Parador con habitaciones',
      'created_at' => Carbon::now()
    ]);

  }

  public function down()
  {
    Schema::dropIfExists('mttipoinst');
  }
}
