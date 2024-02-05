<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

/**
 * Class User
 * @package App\Models
 * @version April 30, 2020, 5:02 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection modelHasRoles
 * @property string name
 * @property string codigo
 * @property string email
 * @property string|\Carbon\Carbon email_verified_at
 * @property string password
 * @property string remember_token
 */
class User extends Authenticatable
{

  public $table = 'users';
  public $model = 'Usuario';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';


  public $fillable = [
    'codigo',
    'username',
    'email',
    'password',
    'remember_token',
    'persona_id',
    'instalacion_id',
    'activo',
    'admin',
    'primary',
    'last_login',
    'token',
    'first_login'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'codigo' => 'string',
    'username' => 'string',
    'email' => 'string',
    'password' => 'string',
    'remember_token' => 'string',
    'activo' => 'string',
    'admin' => 'string',
    'primary' => 'string',
    'persona_id' => 'integer',
    'instalacion_id' => 'integer',
    'last_login' => 'datetime',
    'token' => 'string',
    'first_login' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
    'username' => 'required',
    'email' => 'required',
    'password' => 'required'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   **/
  public function roles()
  {
    return $this->belongsToMany(Role::class, 'model_has_roles', 'user_id', 'role_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/
  public function persona()
  {
    return $this->belongsTo(Persona::class, 'persona_id');
  }
  public function instalacion()
  {
    return $this->belongsTo(MTInstalacion::class, 'instalacion_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function traza()
  {
    return $this->hasMany(Traza::class, 'usuario_id');
  }

  public function getAllPermission()
  {
    $permisos = null;
    foreach ($this::find($this->id)->roles()->get() as $role) {
      foreach (Role::find($role->id)->permisos()->get() as $permiso) {
        $permisos == null ? $permisos .= $permiso->name : $permisos .= ' ' . $permiso->name;
      }
    }
    return $permisos;
  }
}
