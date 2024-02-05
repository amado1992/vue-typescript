<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTHabilidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $habilidades = [
            ['nombre' => 'Habla', 'codigo' => 'H', 'created_at' => Carbon::now()],
            ['nombre' => 'Escribe', 'codigo' => 'E', 'created_at' => Carbon::now()],
            ['nombre' => 'Lee', 'codigo' => 'L', 'created_at' => Carbon::now()]];


        DB::table('mthabilidades')->insert($habilidades);
    }
}
