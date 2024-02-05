<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTAgenteCausalBroteSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'UZZ1',
            'agente_causal_brote'=> 'Staphylococcus Aureus',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'WXY2',
            'agente_causal_brote'=> 'Salmonella',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'AMQ3',
            'agente_causal_brote'=> 'Clostridium Perfringens',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'CAN4',
            'agente_causal_brote'=> 'Escherichia Coli',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'EKR5',
            'agente_causal_brote'=> 'Bacillus Cereus',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'THH6',
            'agente_causal_brote'=> 'Shigella',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'NPJ7',
            'agente_causal_brote'=> 'Norovirus',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'JIA8',
            'agente_causal_brote'=> 'Cólera',
            'tipobrote_id'=> '1',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'PVT9',
            'agente_causal_brote'=> 'Norovirus',
            'tipobrote_id'=> '2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'OBH10',
            'agente_causal_brote'=> 'Cólera',
            'tipobrote_id'=> '2',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'QQY11',
            'agente_causal_brote'=> 'Covid-19',
            'tipobrote_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'NIS12',
            'agente_causal_brote'=> 'AH1N1',
            'tipobrote_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'LWD13',
            'agente_causal_brote'=> 'Legionella Pneumophila',
            'tipobrote_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtagente_causal_brotes')->insert([
            'codigo'=> 'WPA14',
            'agente_causal_brote'=> 'Desconocido',
            'tipobrote_id'=> '3',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtagente_causal_brotes');
    }

}
