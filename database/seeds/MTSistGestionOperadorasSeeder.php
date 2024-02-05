<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MTSistGestionOperadorasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mtsistgestionoperadoras')->insert([
            'desc_Operadora' => 'Administración Extranjera',
            'created_at' => Carbon::now()
        ]);
        DB::table('mtsistgestionoperadoras')->insert([
            'desc_Operadora' => 'Administración Propia',
            'created_at' => Carbon::now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('mtsistgestionoperadoras');
    }
}
