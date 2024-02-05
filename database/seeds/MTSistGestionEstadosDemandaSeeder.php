<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTSistGestionEstadosDemandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('mtsistgestionestadosdemanda')->insert([
          'desc_EstadoDemanda' => 'Solicitada',
          'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestionestadosdemanda')->insert([
          'desc_EstadoDemanda' => 'Conciliada',
          'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestionestadosdemanda')->insert([
        'desc_EstadoDemanda' => 'No Conciliada',
        'created_at' => Carbon::now()
      ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtsistgestionestadosdemanda');
    }
}
