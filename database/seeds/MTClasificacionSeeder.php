<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTClasificacionSeeder extends Seeder
{
    public function run()
    {
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Constructivo',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Mobiliario',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Redes Eléctricas',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Redes hidráulica y sanitaria',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Climatización',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Agua caliente',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Iluminación',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'SADI',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Cerrajeria',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtclasificacion')->insert([
            'nombre'=> 'Equipos',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtclasificacion');
    }
}
