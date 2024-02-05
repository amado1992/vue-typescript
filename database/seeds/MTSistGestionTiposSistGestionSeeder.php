<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTSistGestionTiposSistGestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('mtsistgestiontipossistgestion')->insert([
          'desc_TipoSistGestion' => 'Sistemas de gestión de la calidad',
          'norma_Referencia' => 'NC-ISO 9001',
          'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestiontipossistgestion')->insert([
        'desc_TipoSistGestion' => 'Sistemas de Gestión Ambiental',
        'norma_Referencia' => 'NC-ISO 14001',
        'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestiontipossistgestion')->insert([
        'desc_TipoSistGestion' => 'Sistemas de Gestión de la Seguridad y Salud en el Trabajo',
        'norma_Referencia' => 'NC-ISO 45001',
        'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestiontipossistgestion')->insert([
        'desc_TipoSistGestion' => 'Sistemas de gestión de la inocuidad de los alimentos',
        'norma_Referencia' => 'NC-ISO 22000',
        'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestiontipossistgestion')->insert([
        'desc_TipoSistGestion' => 'Sistemas de Gestión de la Energía',
        'norma_Referencia' => 'NC-ISO 50001',
        'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestiontipossistgestion')->insert([
        'desc_TipoSistGestion' => 'Sistema de Gestión de la I+D+i',
        'norma_Referencia' => 'NC 1307',
        'created_at' => Carbon::now()
      ]);
      DB::table('mtsistgestiontipossistgestion')->insert([
        'desc_TipoSistGestion' => 'Sistema de Gestión Sostenible para establecimientos de alojamiento',
        'norma_Referencia' => 'NC ISO 21401:2018',
        'created_at' => Carbon::now()
      ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtsistgestiontipossistgestion');
    }
}
