<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTCadenaHoteleraSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtcadena_hoteleras')->insert([
            'activo'=> '1',
            'cadena_hotelera'=> 'Gran Caribe',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcadena_hoteleras')->insert([
            'activo'=> '1',
            'cadena_hotelera'=> 'Cubanacan',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcadena_hoteleras')->insert([
            'activo'=> '1',
            'cadena_hotelera'=> 'Islazul',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcadena_hoteleras')->insert([
            'activo'=> '1',
            'cadena_hotelera'=> 'Gaviota',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtcadena_hoteleras');
    }

}
