<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTOrigenCasoSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtorigen_casos')->insert([
            'codigo'=> 'YIP1',
            'origen_caso'=> 'Químico',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtorigen_casos')->insert([
            'codigo'=> 'GHZ2',
            'origen_caso'=> 'Biológico',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtorigen_casos')->insert([
            'codigo'=> 'HRY3',
            'origen_caso'=> 'Desconocido',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtorigen_casos');
    }

}
