<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Super Admin del sitio*/
        $passwordHash = password_hash('12345678', PASSWORD_BCRYPT);
        DB::table('users')->insert([
            'username' => 'superuser',
            'password' => $passwordHash,
            'email' => 'superuser@get.tur.cu',
            'activo' => true,
            'admin' => true,
            'primary' => true,
            'persona_id' => 1,
            'instalacion_id' => 1,
            'last_login' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $user = DB::table('users')
            ->where('username', 'superuser')
            ->select('users.id', 'users.email')
            ->first();

        $rol = DB::table('roles')
            ->where('name', 'SuperAdmin')
            ->select('roles.id', 'roles.name')
            ->first();

        DB::table('model_has_roles')->insert([
            'role_id' => $rol->id,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
