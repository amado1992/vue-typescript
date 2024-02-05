<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTCategoriaAlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtcategoria')->insert([
            'nombre' => '1er Nivel Tecnológico',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtcategoria')->insert([
            'nombre' => '2do Nivel Tecnológico',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtcategoria')->insert([
            'nombre' => '3er Nivel Tecnológico',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
