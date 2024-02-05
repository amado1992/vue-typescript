<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoVAServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Auto',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Jeep',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Moto Sencilla',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Moto con Sidecar',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Moto Eléctrica',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Moto-Triciclo',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Panel',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Camioneta',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Ómnibus (> 30 Asiento)',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Minibús (17 a 30 Asiento)',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Microbús (+8 a 16 Asiento)',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Camión Volteo',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Camión Refrigerado',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Camión Plancha-Rampa',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Camión Furgón',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Cuña Tractora',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Ambulancia',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Remolque-Semirremolque',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Transportador de Autos',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Vehículo Servicios/Técnicos',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Vehículo/Auxilios',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'ATV',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Cisterna Agua',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipovaservicios')->insert([
            'tipo'=>'Cisterna Combustible',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mttipovaservicios');
    }
}
