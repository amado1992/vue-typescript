<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTOrigenBroteSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtorigen_brotes')->insert([
            'codigo'=> 'MPZ1',
            'origen_brote'=> 'Químico',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtorigen_brotes')->insert([
            'codigo'=> 'ICW2',
            'origen_brote'=> 'Biológico',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtorigen_brotes')->insert([
            'codigo'=> 'WLH3',
            'origen_brote'=> 'Desconocido',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtorigen_brotes');
    }

}
