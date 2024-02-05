<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoVEspecializadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Compreso',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Montacarga Diésel',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Montacarga Eléctrico',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Tractor con Aditamento',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Tractor con Neumáticos',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Tractor con estera',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Limpiadora de Playa',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Equipo Multipropósito',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Autoholmigonera',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Bulldozer',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Motovolqueta',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Cargador',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Motoniveladora',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Retroexcavadora',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Segadora',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Hormigonera',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Recolector Basura',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Barredor de Calle',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Limpia-Fosas',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Grúa',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Contenedor',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Camión Limpia Contenedor',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovespecializados')->insert([
            'tipo'=>'Cangrejo',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mttipovespecializados');
    }
}
