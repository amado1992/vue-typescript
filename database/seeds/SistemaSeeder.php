<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtsistemas')->insert([
            'nombre' => 'Piscina',
            'descripcion' => 'Piscina en buen estado',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('mtsistemas')->insert([
            'nombre' => 'Camara fría',
            'descripcion' => 'Camara fría en buen estado',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
