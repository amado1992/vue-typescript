<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTOaceSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINCEX',
            'oace'=> 'Ministerio del Comercio Exterior y la Inversión Extranjera',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MEP',
            'oace'=> 'Ministerio de Economía y Planificación',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'BCC',
            'oace'=> 'Banco Central de Cuba',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MFP',
            'oace'=> 'Ministerio de Finanzas y Precios',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINFAR',
            'oace'=> 'Ministerio de las Fuerzas Armadas Revolucionarias',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> 't1rue',
            'siglas'=> 'MININT',
            'oace'=> 'Ministerio del Interior',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MES',
            'oace'=> 'Ministerio de Educación Superior',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MICONS',
            'oace'=> 'Ministerio de Construcción',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MTSS',
            'oace'=> 'Ministerio de Trabajo y Seguridad Social',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINCIN',
            'oace'=> 'Ministerio de Comercio Interior',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINEM',
            'oace'=> 'Ministerio de Energía y Minas',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINAL',
            'oace'=> 'Ministerio de Industria Alimentaria',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINDUS',
            'oace'=> 'Ministerio de Industrias',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINAG',
            'oace'=> 'Ministerio de Agricultura',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINSAP',
            'oace'=> 'Ministerio de Salud Pública',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINTUR',
            'oace'=> 'Ministerio de Turismo',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'CITMA',
            'oace'=> 'Ministerio de Ciencia, Tecnología y Medio Ambiente',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MINCULT',
            'oace'=> 'Ministerio de Cultura ',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtoaces')->insert([
            'activo'=> '1',
            'siglas'=> 'MITRANS',
            'oace'=> 'Ministerio de Transporte ',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtoaces');
    }

}
