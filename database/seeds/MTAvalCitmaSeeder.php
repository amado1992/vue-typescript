<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTAvalCitmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtaval_citma_nomenclador')->insert([
            'codigo'=>'aval21v',
            'estado'=>'Vencida',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtaval_citma_nomenclador')->insert([
            'codigo'=>'aval21t',
            'estado'=>'En trámite',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtaval_citma_nomenclador')->insert([
            'codigo'=>'aval21a',
            'estado'=>'Vigente',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtaval_citma_nomenclador');
    }
}
