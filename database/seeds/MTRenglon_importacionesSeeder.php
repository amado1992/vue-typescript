<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTRenglon_importacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtrenglons')->insert([
            'nombre' => 'Bienes de Consumo',
            'created_at' => Carbon::now()
        ]);
        DB::table('mtrenglons')->insert([
            'nombre' => 'Suministros para Inversiones ',
            'created_at' => Carbon::now()
        ]);
        DB::table('mtrenglons')->insert([
            'nombre' => 'Bienes Intermedios',
            'created_at' => Carbon::now()
        ]);
        DB::table('mtrenglons')->insert([
            'nombre' => 'Consignaciones',
            'created_at' => Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtrenglons');
    }
}
