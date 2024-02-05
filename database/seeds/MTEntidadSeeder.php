<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTEntidadSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('mtentidad')->insert([
      'nombre' => 'Servisa',
      'codigo' => 'Servisa',
      'osde_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtentidad')->insert([
      'nombre' => 'Cubatur',
      'codigo' => '0023',
      'osde_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtentidad')->insert([
      'nombre' => 'Viajes Cubanacan',
      'codigo' => '0024',
      'osde_id' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('mtentidad')->insert([
      'nombre' => 'Havanatur',
      'codigo' => '0025',
      'osde_id' => 1,
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtentidad');
  }
}
