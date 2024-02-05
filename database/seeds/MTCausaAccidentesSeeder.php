<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTCausaAccidentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtcausaaccidentes')->insert([
            'causa_accidente'=>'Exceso de velocidad',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtcausaaccidentes')->insert([
            'causa_accidente'=>'No atender el control del vehículo',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtcausaaccidentes')->insert([
            'causa_accidente'=>'Desperfectos técnicos',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtcausaaccidentes')->insert([
            'causa_accidente'=>'Ingestión de bebidas alchólicas y otras sustancias',
            'created_at'=> Carbon::now()
        ]);

        DB::table('mtcausaaccidentes')->insert([
            'causa_accidente'=>'Otros',
            'created_at'=> Carbon::now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mtcausaaccidentes');
    }
}
