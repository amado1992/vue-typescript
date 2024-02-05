<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTOsdeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtosde')->insert([
            'activo'=>'1',
            'nombre'=>'MINTUR',
            'tipo'=>'Hotelera',
            'provincia_id'=>'3',
            'oace_id'=>'1',
            'created_at'=> Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtosde');
    }
}
