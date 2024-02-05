<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtsector')->insert([
            'nombre' => 'Estatal',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtsector')->insert([
            'nombre' => 'No Estatal',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('mtsector');
    }
}
