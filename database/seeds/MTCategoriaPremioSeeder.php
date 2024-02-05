<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MTCategoriaPremioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtcategoriapremio')->insert([
            'nombre' => 'Nacional',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('mtcategoriapremio')->insert([
            'nombre' => 'Internacional',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtcategoriapremio');
    }
}
