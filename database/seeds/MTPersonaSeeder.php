<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTPersonaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('event_persona')->insert([
      'codigo' => Str::random(10),
      'nombre_completo' => 'Superuser',
      'correo' => 'superuser@get.tur.cu',
      'telefono' => '5555555',
      'activo' => 1,
      'created_at' => Carbon::now()
    ]);
    DB::table('event_persona')->insert([
      'codigo' => Str::random(10),
      'nombre_completo' => 'Jhon Doe',
      'correo' => 'jhon@fakemail.com',
      'telefono' => '5555555',
      'activo' => 1,
      'created_at' => Carbon::now()
    ]);
  }

  public function down()
  {
    Schema::dropIfExists('event_persona');
  }
}
