<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTCostoNoConformidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtcostosnoconformidades')->insert([
            'codigo' => Str::random(10),
            'nombre' => 'Compensación a clientes por servicios deficientes',
            'tipo' => 'No Conformidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtcostosnoconformidades')->insert([
            'codigo' => Str::random(10),
            'nombre' => 'Gastos de reubicación',
            'tipo' => 'No Conformidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtcostosnoconformidades')->insert([
            'codigo' => Str::random(10),
            'nombre' => 'Reclamaciones a turoperadores',
            'tipo' => 'No Conformidad',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
