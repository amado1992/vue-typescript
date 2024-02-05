<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoBroteSeeder extends Seeder
{
    public function run()
    {
        DB::table('mttipo_brotes')->insert([
            'codigo'=> 'JXZ1',
            'tipo_brote'=> 'Enfermedad de Transmisión Alimentaria (ETA)',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mttipo_brotes')->insert([
            'codigo'=> 'SQR2',
            'tipo_brote'=> 'Enfermedad Diarreica Aguda (EDA)',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mttipo_brotes')->insert([
            'codigo'=> 'KZD3',
            'tipo_brote'=> 'Infección Respiratoria Aguda (IRA)',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mttipo_brotes');
    }

}
