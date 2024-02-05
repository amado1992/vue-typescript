<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTAgenteCausalCasoSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'FKB1',
            'agente_causal_caso'=> 'Staphylococcus Aureus',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'RVZ2',
            'agente_causal_caso'=> 'Salmonella',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'CJU3',
            'agente_causal_caso'=> 'Clostridium Perfringens',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'OQJ4',
            'agente_causal_caso'=> 'Escherichia Coli',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'CRP5',
            'agente_causal_caso'=> 'Bacillus Cereus',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'QEG6',
            'agente_causal_caso'=> 'Shigella',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'HTZ7',
            'agente_causal_caso'=> 'Norovirus',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'XUG8',
            'agente_causal_caso'=> 'Cólera',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'KES9',
            'agente_causal_caso'=> 'Desconocido',
            'tipocaso_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'VWQ10',
            'agente_causal_caso'=> 'Norovirus',
            'tipocaso_id'=> '2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'XCF11',
            'agente_causal_caso'=> 'Cólera',
            'tipocaso_id'=> '2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'CAH12',
            'agente_causal_caso'=> 'Desconocido',
            'tipocaso_id'=> '2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'IGL13',
            'agente_causal_caso'=> 'Covid-19',
            'tipocaso_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'WAN14',
            'agente_causal_caso'=> 'AH1N1',
            'tipocaso_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'HST15',
            'agente_causal_caso'=> 'Legionella Pneumophila',
            'tipocaso_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_casos')->insert([
            'codigo'=> 'PMO16',
            'agente_causal_caso'=> 'Desconocido',
            'tipocaso_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtagente_causal_casos');
    }

}
