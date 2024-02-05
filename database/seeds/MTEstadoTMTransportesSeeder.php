<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTEstadoTMTransportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtestadotmtransportes')->insert([
            'estado'=>'Bueno',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtestadotmtransportes')->insert([
            'estado'=>'Malo',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtestadotmtransportes')->insert([
            'estado'=>'Regular',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtestadotmtransportes');
    }
}
