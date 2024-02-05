<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTPatogenoIdentificadoSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'CIW1',
            'patogeno_identificado'=> 'Staphylococcus aureus',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'GAD2',
            'patogeno_identificado'=> 'Salmonella',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'NVS3',
            'patogeno_identificado'=> 'Clostridium perfringens',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'UIR4',
            'patogeno_identificado'=> 'Escherichia coli',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'GVP5',
            'patogeno_identificado'=> 'Bacillus cereus',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'MKB6',
            'patogeno_identificado'=> 'Shigella',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtpatogeno_identificado')->insert([
            'codigo'=> 'LZC7',
            'patogeno_identificado'=> 'Legionella pneumophila',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtpatogeno_identificado');
    }

}
