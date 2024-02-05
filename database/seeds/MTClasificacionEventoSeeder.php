<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTClasificacionEventoSeeder extends Seeder
{
    public function run()
    {
        DB::table('mtclasificacion_eventos')->insert([
            'codigo'=> 'WYW1',
            'clasificacion_evento'=> 'Foco',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion_eventos')->insert([
            'codigo'=> 'LHS2',
            'clasificacion_evento'=> 'Muestra Positiva',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion_eventos')->insert([
            'codigo'=> 'LLJ3',
            'clasificacion_evento'=> 'Caso',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion_eventos')->insert([
            'codigo'=> 'WYW4',
            'clasificacion_evento'=> 'Brote',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtclasificacion_eventos');
    }

}
