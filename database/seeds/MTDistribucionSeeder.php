<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTDistribucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtdistribucion')->insert([
          'nombre'=> 'Mayorista',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mtdistribucion')->insert([
          'nombre'=> 'Minorista',
          'created_at' => now(),
          'updated_at' => now()
        ]);
    }
}
