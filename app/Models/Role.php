<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\Models
 * @version April 27, 2020, 7:36 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection modelHasRoles
 * @property \Illuminate\Database\Eloquent\Collection roleHasPermissions
 * @property string name
 * @property string codigo
 * @property string guard_name
 * @property string description
 * @property boolean enabled
 * @property boolean blocked
 */
class Role extends Model
{

    public $table = 'roles';
    public $model = 'Role';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'codigo',
        'name',
        'guard_name',
        'description',
        'enabled',
        'blocked'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'name' => 'string',
        'guard_name' => 'string',
        'description' => 'string',
        'enabled' => 'string',
        'blocked' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function modelHasRoles()
    {
        return $this->hasMany(\App\Models\ModelHasRole::class, 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function permisos()
    {
        return $this->belongsToMany(Permission::class,'role_has_permissions', 'role_id','permission_id');
    }


}
