<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTCategoriaInstalacionSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '4',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '5',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '1ra',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '2da',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '3ra',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '4ta',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> '5 plus',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> 'A',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> 'B',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> 'C',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> 'Hotel E',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcategoria_instalacions')->insert([
            'activo'=> '1',
            'categoria_instalacion'=> 'Sin Categoría',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtcategoria_instalacions');
    }

}
