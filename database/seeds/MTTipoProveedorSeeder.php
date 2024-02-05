<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTTipoProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mttipoproveedor')->insert([
          'nombre'=> 'Empresa Estatal Socialista',
          'created_at' => now(),
          'updated_at' => now()
        ]);
        DB::table('mttipoproveedor')->insert([
          'nombre'=> 'Forma Productiva',
          'created_at' => now(),
          'updated_at' => now()
        ]);

    }

    public function down()
    {
        Schema::dropIfExists('mttipoproveedor');
    }
}
