<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTEvaluacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evaluaciones = [
            ['nombre' => 'Excelente', 'codigo' => 'E', 'created_at' => Carbon::now()],
            ['nombre' => 'Bien',  'codigo' => 'B', 'created_at' => Carbon::now()],
            ['nombre' => 'Regular',  'codigo' => 'R', 'created_at' => Carbon::now()]];


        DB::table('mtevaluaciones')->insert($evaluaciones);
    }
}
