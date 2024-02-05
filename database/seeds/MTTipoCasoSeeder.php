<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoCasoSeeder extends Seeder
{
    public function run()
    {
        DB::table('mttipo_casos')->insert([
            'codigo'=> 'ABP1',
            'tipo_caso'=> 'Enfermedad de Transmisión Alimentaria (ETA)',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mttipo_casos')->insert([
            'codigo'=> 'MTL2',
            'tipo_caso'=> 'Enfermedad Diarreica Aguda (EDA)',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mttipo_casos')->insert([
            'codigo'=> 'KLR3',
            'tipo_caso'=> 'Infección Respiratoria Aguda (IRA)',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mttipo_casos');
    }

}
