<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTClaseMTransportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtclasemtransportes')->insert([
            'clase'=>'Ligero',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtclasemtransportes')->insert([
            'clase'=>'Pesado',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtclasemtransportes');
    }
}
