<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTClasificacionAccidentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtclasificacionaccidentes')->insert([
            'clasificacion_accidente'=>'Leve',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtclasificacionaccidentes')->insert([
            'clasificacion_accidente'=>'Grave',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtclasificacionaccidentes')->insert([
            'clasificacion_accidente'=>'Catastrófico',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtclasificacionaccidentes');
    }
}
