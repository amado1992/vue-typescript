<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTSistGestionDemandaServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtsistgestiondemandaservicios')->insert([
          'cod_DemandaServicio' => 'IN',
          'desc_DemandaServicio' => 'Inicial',
          'created_at' => Carbon::now()
        ]);
        DB::table('mtsistgestiondemandaservicios')->insert([
          'cod_DemandaServicio' => 'MO',
          'desc_DemandaServicio' => 'Modificación del alcance',
          'created_at' => Carbon::now()
        ]);
        DB::table('mtsistgestiondemandaservicios')->insert([
          'cod_DemandaServicio' => 'RE',
          'desc_DemandaServicio' => 'Renovación',
          'created_at' => Carbon::now()
        ]);
    }

    public function down()
    {
      Schema::dropIfExists('mtsistgestiondemandaservicios');
    }
}
