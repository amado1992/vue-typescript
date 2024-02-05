<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTLicSanitariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtlic_sanitaria_nomenclador')->insert([
            'codigo'=>'lic21r',
            'estado'=>'Retirada',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtlic_sanitaria_nomenclador')->insert([
            'codigo'=>'lic21t',
            'estado'=>'En trámite',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtlic_sanitaria_nomenclador')->insert([
            'codigo'=>'lic21a',
            'estado'=>'Vigente',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtlic_sanitaria_nomenclador');
    }
}
