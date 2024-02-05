<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoFocoSeeder extends Seeder
{
    public function run()
    {

        DB::table('mttipo_focos')->insert([
            'codigo'=> 'KMT1',
            'tipo_foco'=> 'Aedes Aegypti',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mttipo_focos')->insert([
            'codigo'=> 'BOQ2',
            'tipo_foco'=> 'Caracol Gigante Africano',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mttipo_focos');
    }

}
