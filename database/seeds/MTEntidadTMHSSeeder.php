<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTEntidadTMHSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = [
            ['nombre' => 'OSDE', 'codigo' => 'osde', 'created_at' => Carbon::now()],
            ['nombre' => 'Escuela Ramal', 'codigo' => 'scr', 'created_at' => Carbon::now()],
            ['nombre' => 'Infotur', 'codigo' => 'infotur', 'created_at' => Carbon::now()],
            ['nombre' => 'Delegación Territorial', 'codigo' => 'dt', 'created_at' => Carbon::now()]];


        DB::table('mtentidadestmhs')->insert($lists);
    }
}
