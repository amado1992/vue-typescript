<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTDetectadoPorSeeder extends Seeder
{
    public function run()
    {
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'WLP1',
            'detectado_por'=> 'MINSAP',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'RWP2',
            'detectado_por'=> 'OTN',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'CYG3',
            'detectado_por'=> 'Delegación Territorial',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'SDD4',
            'detectado_por'=> 'MINTUR',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'ZYD5',
            'detectado_por'=> 'Turoperador',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'HBF6',
            'detectado_por'=> 'AAVV',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdetectado_por')->insert([
            'codigo'=> 'TMB7',
            'detectado_por'=> 'CICDC (Centro de Investigaciones Científicas de la Defensa Civil)',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtdetectado_por');
    }

}
