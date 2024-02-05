<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Super Admin del sitio*/
        DB::table('user_sa')->insert([
            'id' => 1,
            'uuid' => 'superuser',
            'nombre' => 'superuser',
            'apellidos' => 'superuser',
            'email' => 'superuser@get.tur.cu',
            'activo' => true,
            'admin' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('user_sa');
    }
}
