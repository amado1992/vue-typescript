<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostoConformidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtcostosconformidades')->insert([
            'codigo' => Str::random(10),
            'nombre' => 'Capacitación',
            'tipo' => 'Conformidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtcostosconformidades')->insert([
        'codigo' => Str::random(10),
        'nombre' => 'Adiestramiento',
        'tipo' => 'Conformidad',
        'created_at' => now(),
        'updated_at' => now()
       ]);
        DB::table('mtcostosconformidades')->insert([
            'codigo' => Str::random(10),
            'nombre' => 'Mantenimiento, calibración y verificación de equipos',
            'tipo' => 'Conformidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
