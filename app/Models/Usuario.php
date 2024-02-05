<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Usuario
 * @package App\Models
 * @version January 8, 2021, 2:10 pm CST
 *
 * @property \App\Models\ModelHasRole $id
 * @property string $uuid
 * @property string $nombre
 * @property string $apellidos
 * @property string $email
 * @property boolean $admin
 * @property boolean $activo
 */
class Usuario extends Model
{

    public $table = 'user_sa';
    public $model = 'Usuario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'uuid',
        'nombre',
        'apellidos',
        'email',
        'admin',
        'activo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'uuid' => 'string',
        'nombre' => 'string',
        'apellidos' => 'string',
        'email' => 'string',
        'admin' => 'boolean',
        'activo' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'uuid' => 'required|string|max:255',
        'nombre' => 'required|string|max:191',
        'apellidos' => 'required|string|max:191',
        'email' => 'required|string|max:191',
        'admin' => 'required|boolean',
        'activo' => 'required|boolean',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function rolesSa()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'user_sa_id', 'role_id');
    }
    public function getAllPermission($id)
    {
        $permisos = null;
        foreach ($this::find($id)->rolesSa()->get() as $role) {
            foreach (Role::find($role->id)->permisos()->get() as $permiso) {
                $permisos == null ? $permisos .= $permiso->name : $permisos .= ' ' . $permiso->name;
            }
        }
        return $permisos;
    }
}
