<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTValorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtvalores')->insert([
            'nombre' => 'A+B',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('mtvalores')->insert([
            'nombre' => 'Turismo deportivo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('mtvalores')->insert([
            'nombre' => 'Naturaleza',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('mtvalores')->insert([
            'nombre' => 'Náutica',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('mtvalores')->insert([
            'nombre' => 'Transportación',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
