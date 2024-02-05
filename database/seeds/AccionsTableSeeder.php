<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_naccion')->insert([
            'accion' => 'adicionar',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'modificar',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'eliminar',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'acceder al sitio',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'salir del sitio',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'cambiar contraseña',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'imprimir',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'enviar correo',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('event_naccion')->insert([
            'accion' => 'facturar',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('event_naccion');
    }
}
