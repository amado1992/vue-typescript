<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTProducto_flujoMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('producto')->insert([
            'codigo'=> Str::random(10),
            'nombre'=>'Producto_1',  
            'cantidad'=> 5,  
            'familia_id'=> 1,
            'unidadmedida_id'=> 1,
            'created_at'=> Carbon::now()
        ]);
        DB::table('producto')->insert([
            'codigo'=> Str::random(10),
            'nombre'=>'Producto_2',  
            'cantidad'=> 3,  
            'familia_id'=> 1,
            'unidadmedida_id'=> 1,
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('producto');
    }
}
