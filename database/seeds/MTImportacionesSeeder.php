<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTImportacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtimportacions')->insert([
            'mes'=> 7,  
            'anno'=> 2021,  
            'plan'=> 15,  
            'real'=> 12,
            'entidad_id'=> 1,
            'indicador_id'=> 1,
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtimportacions')->insert([
            'mes'=> 5,  
            'anno'=> 2021,  
            'plan'=> 50,  
            'real'=> 20,
            'entidad_id'=> 2,
            'indicador_id'=> 1,
            'created_at'=> Carbon::now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('mtimportacions');
    }
}
