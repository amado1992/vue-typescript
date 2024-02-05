<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package App\Models
 * @version April 27, 2020, 7:38 pm UTC
 *
 * @property \App\Models\EventOptionsMenu optionMenu
 * @property \Illuminate\Database\Eloquent\Collection roleHasPermissions
 * @property string name
 * @property string guard_name
 * @property integer option_menu_id
 * @property boolean blocked
 */
class Permission extends Model
{

    public $table = 'permissions';
    public $model = 'Permisos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'guard_name',
        'option_menu_id',
        'blocked'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'guard_name' => 'string',
        'option_menu_id' => 'integer',
        'blocked' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'guard_name' => 'required',
        'option_menu_id' => 'required',
        'blocked' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function optionMenu()
    {
        return $this->belongsTo(\App\Models\EventOptionsMenu::class, 'option_menu_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function roleHasPermissions()
    {
        return $this->hasMany(\App\Models\RoleHasPermission::class, 'permission_id');
    }
}
