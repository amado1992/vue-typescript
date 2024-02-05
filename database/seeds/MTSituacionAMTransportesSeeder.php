<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTSituacionAMTransportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtsituacionamtransportes')->insert([
            'situacion_actual'=>'Trabajando',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtsituacionamtransportes')->insert([
            'situacion_actual'=>'Alta',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtsituacionamtransportes')->insert([
            'situacion_actual'=>'Paralizado',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtsituacionamtransportes')->insert([
            'situacion_actual'=>'Propuesto a Baja',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtsituacionamtransportes')->insert([
            'situacion_actual'=>'Baja Técnica',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtsituacionamtransportes')->insert([
            'situacion_actual'=>'Baja Turística',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtestadotmtransportes');
    }
}
