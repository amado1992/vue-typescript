<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoVTuristicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipovturisticos')->insert([
            'tipo'=>'Trencito',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovturisticos')->insert([
            'tipo'=>'Coche de Trencito',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovturisticos')->insert([
            'tipo'=>'Panel (carga)',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovturisticos')->insert([
            'tipo'=>'Bicicleta',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mttipovturisticos');
    }
}
