<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTIndicador_importacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtindicadors')->insert([ 
            'nombre'=>'Indicador_1',
            'renglon_id'=>'1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtindicadors')->insert([
             
            'nombre'=>'Indicador_2',
            'renglon_id'=>'1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtindicadors')->insert([
            
            'nombre'=>'Indicador_3',
            'renglon_id'=>'2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtindicadors')->insert([
            
            'nombre'=>'Indicador_4',
            'renglon_id'=>'3',
            'created_at'=> Carbon::now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('mtindicadors');
    }
}
