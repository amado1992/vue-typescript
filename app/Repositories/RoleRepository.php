<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleRepository
 * @package App\Repositories
 * @version April 27, 2020, 7:36 pm UTC
 */
class RoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name',
        'description',
        'enabled',
        'blocked'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Role::class;
    }

    public function getAllPermission($payload)
    {
        return DB::table('permissions')->get(['id', 'name', 'visible_name']);
    }

    public function getActiveRole($payload)
    {
        return DB::table($this->model->getTable())->where([['enabled', $payload], ['primary', '!=', true]])->orderByDesc('created_at')->get();
    }
    public function genareteCod()
    {
        $obj = $this->model->orderBy('id', 'desc')->first();
        return "rol-" . (str_pad((($obj === null) ? 1 : $obj->id + 1), 2, '0', STR_PAD_LEFT));
    }
    public function getNombreRol()
    {
        return DB::table('roles')
            ->orderBy('roles.name')
            ->select('roles.name')
            ->get();
    }
}
