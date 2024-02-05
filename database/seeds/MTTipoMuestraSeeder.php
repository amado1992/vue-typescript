<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoMuestraSeeder extends Seeder
{
    public function run()
    {

        DB::table('mttipo_muestras')->insert([
            'codigo'=> 'YVQ1',
            'tipo_muestra'=> 'Agua',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mttipo_muestras')->insert([
            'codigo'=> 'GGI2',
            'tipo_muestra'=> 'Alimentos',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mttipo_muestras');
    }

}
