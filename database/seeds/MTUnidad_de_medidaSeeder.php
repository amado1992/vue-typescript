<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTUnidad_de_medidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtunidadmedida')->insert([
            'nombre'=>'Gr',  
            'created_at'=> Carbon::now()
        ]);
        DB::table('producto')->insert([
            'nombre'=>'Kg',  
            'created_at'=> Carbon::now()
        ]);
        DB::table('producto')->insert([
            'nombre'=>'Ml',  
            'created_at'=> Carbon::now()
        ]);
        DB::table('producto')->insert([
            'nombre'=>'L',  
            'created_at'=> Carbon::now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('mtunidadmedida');
    }
}
