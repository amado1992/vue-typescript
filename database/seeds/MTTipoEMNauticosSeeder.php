<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoEMNauticosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Yate',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Lancha',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Motovelero Monocasco',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Catamarán Motovelero',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Velero Monocasco o bote vela',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Catamarán Velero',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Trimarán',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Kayak',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Moto Acuática',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Bote',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Bicicleta Acuática',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Tabla',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Bote Inflable o Balsa',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Patana',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Winsurf',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Paddle board',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Barco',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Medio Náutico para Minusválidos',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipoemnauticos')->insert([
            'tipo'=>'Otros (Ski, Banana, etc.)',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mttipoemnauticos');
    }
}
