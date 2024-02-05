<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTProvinciaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('mtprovincia')->insert([
      'nombre' => 'La Habana',
      'created_at' => Carbon::now()
    ]);

    DB::table('mtprovincia')->insert([
      'nombre' => 'Pinar del Rio',
      'created_at' => Carbon::now()
    ]);

    DB::table('mtprovincia')->insert([
      'nombre' => 'Mayabeque',
      'created_at' => Carbon::now()
    ]);

    DB::table('mtprovincia')->insert([
      'nombre' => 'Cienfuegos',
      'created_at' => Carbon::now()
    ]);

    DB::table('mtprovincia')->insert([
      'nombre' => 'Isla de la Juventud',
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('mtprovincia');
  }
}
