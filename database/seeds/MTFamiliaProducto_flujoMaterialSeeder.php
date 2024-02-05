<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTFamiliaProducto_flujoMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('familia')->insert([
            'nombre'=>'familia_producto1',  
            'descripcion'=>'Familia de productos #1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('familia')->insert([
            'nombre'=>'familia_producto2',  
            'descripcion'=>'Familia de productos #2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('familia')->insert([
            'nombre'=>'familia_producto3',  
            'descripcion'=>'Familia de productos #3',
            'created_at'=> Carbon::now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('familia');
    }
}
