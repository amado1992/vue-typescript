<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTUbicacionMEMNauticosTransportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtubicacionmemnauticos')->insert([
            'ubicacion'=>'Intraborda',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtubicacionmemnauticos')->insert([
            'ubicacion'=>'Fuera de borda',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtubicacionmemnauticos');
    }
}
