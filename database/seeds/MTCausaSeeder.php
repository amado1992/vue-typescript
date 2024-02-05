<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTCausaSeeder extends Seeder
{
    public function run()
    {

        DB::table('mtcausa')->insert([
            'nombre'=> 'Mtto. imprevisto',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcausa')->insert([
            'nombre'=> 'Reparación Capital',
            'created_at'=> Carbon::now()
        ]);
        DB::table('mtcausa')->insert([
            'nombre'=> 'Reposición',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtcausa');
    }

}
