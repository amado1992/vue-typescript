<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoFlotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipoflotas')->insert([
            'tipo_flota'=>'Administrativo y de Servicio',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoflotas')->insert([
            'tipo_flota'=>'Especializado',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoflotas')->insert([
            'tipo_flota'=>'Turístico',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoflotas')->insert([
            'tipo_flota'=>'Embarcaciones o medios náuticos',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mttipoflotas');
    }
}
