<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoCMTransportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipocmtransportes')->insert([
            'combustible'=>'Gasolina',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mttipocmtransportes')->insert([
            'combustible'=>'Diésel',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mttipocmtransportes');
    }
}
