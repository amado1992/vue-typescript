<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'SuperAdmin',
            'guard_name' => 'web',
            'description' => '',
            'enabled' => true,
            'primary' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $rol = DB::table('roles')
            ->select('id')
            ->where('name', 'SuperAdmin')
            ->first();

        $permission = DB::table('permissions')
            ->select('permissions.id')
            ->get();

        foreach ($permission as $p) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $p->id,
                'role_id' => $rol->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
