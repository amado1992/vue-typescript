<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTDeteccionSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtdeteccion')->insert([
            'codigo'=> 'D1',
            'deteccion'=> 'Interna',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtdeteccion')->insert([
            'codigo'=> 'D2',
            'deteccion'=> 'Externa',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtdeteccion');
    }

}
