<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoEvalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = [
            ['nombre' => 'Primera evaluación', 'codigo' => 'primera', 'created_at' => Carbon::now()],
            ['nombre' => 'Segunda evaluación', 'codigo' => 'segunda', 'created_at' => Carbon::now()]];


        DB::table('mttiposevals')->insert($lists);
    }
}
