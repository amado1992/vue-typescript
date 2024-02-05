<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTAvalApciSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtaval_apci_nomenclador')->insert([
            'codigo'=>'aval021v',
            'estado'=>'Vencida',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtaval_apci_nomenclador')->insert([
            'codigo'=>'aval021t',
            'estado'=>'En trámite',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtaval_apci_nomenclador')->insert([
            'codigo'=>'aval021a',
            'estado'=>'Vigente',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtaval_apci_nomenclador');
    }
}
