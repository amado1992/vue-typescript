<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTVictimaAccidentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtvictimaaccidentes')->insert([
            'victima_accidente'=>'Peatones',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtvictimaaccidentes')->insert([
            'victima_accidente'=>'Ciclos',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtvictimaaccidentes')->insert([
            'victima_accidente'=>'Animales',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtvictimaaccidentes');
    }
}
