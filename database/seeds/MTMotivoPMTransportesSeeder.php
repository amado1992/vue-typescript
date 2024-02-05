<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTMotivoPMTransportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtmotivopmtransportes')->insert([
            'motivo'=>'ATM (falta de abastecimiento de piezas e insumos)',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtmotivopmtransportes')->insert([
            'motivo'=>'Taller',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtmotivopmtransportes')->insert([
            'motivo'=>'Máquina o Motor',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtmotivopmtransportes')->insert([
            'motivo'=>'Otros',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtmotivopmtransportes');
    }
}
