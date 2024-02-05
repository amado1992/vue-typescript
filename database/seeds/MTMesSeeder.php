<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTMesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtmes')->insert([
            'nombre'=>'ENERO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'FEBRERO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'MARZO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'ABRIL',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'MAYO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'JUNIO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'JULIO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'AGOSTO',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'SEPTIEMBRE',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'OCTUBRE',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'NOBIEMBRE',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtmes')->insert([
            'nombre'=>'DICIEMBRE',
            'created_at'=> Carbon::now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('mtmes');
    }
}
