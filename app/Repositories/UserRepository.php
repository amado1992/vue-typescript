<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version April 30, 2020, 5:02 pm UTC
 */
class UserRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'username',
    'email',
    'password',
    'remember_token'
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
    return User::class;
  }

  public function getUsuarioTotal()
  {
    return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
  }

  public function getUsuarioRolTotal()
  {
    return DB::table('model_has_roles')->orderByDesc('created_at')->get();
  }

  public function getBuscarCorreo($correo)
  {
    return DB::table('users')->where('email', '=', $correo)->get();
  }

  public function genareteCod()
  {
    $obj = $this->model->orderBy('id', 'desc')->first();
    return "usr-" . (str_pad((($obj === null) ? 1 : $obj->id + 1), 2, '0', STR_PAD_LEFT));
  }

  public function getNombreUser()
  {
    return DB::table('users')
      ->orderBy('users.username')
      ->select('users.username')
      ->get();
  }

  public function getloadFirstLogin($user)
  {
    return $this->model->where([['username', $user], ['first_login', 1]])->get();
  }

  public function getAllPaginateUser($request)
  {
    $filter = $request['search'];
    $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
    $callBackNombre = EventsUtil::callBack('nombre_completo', 'like', '%' . $filter . '%');
    $callBackCorreo = EventsUtil::callBack('correo', 'like', '%' . $filter . '%');
    return $this->model->with([
      'roles:model_has_roles.role_id,name',
      'persona:id,nombre_completo,correo,telefono',
      'instalacion:id,nombre'
    ])
      ->where(function ($builder) use ($filter, $callBack, $callBackNombre, $callBackCorreo) {
        $builder
          ->where('primary', '=', 0)
          ->where('username', 'like', '%' . $filter . '%')
          ->orWhereHas('persona', $callBackNombre)
          ->orWhereHas('persona', $callBackCorreo)
          ->orWhereHas('instalacion', $callBack);
      })
      ->where('username', '!=', 'superuser')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }
  public function getInstalacion($id)
  {
    return DB::table('mtinstalacion')
      ->where('id', '=', $id)
      ->get()->toArray();
  }
  public function listUserEmailByPermission($permission, $instalacionId = null)
  {
    $query = DB::table('users')
      ->join('model_has_roles', 'model_has_roles.user_id', 'users.id')
      ->join('role_has_permissions', 'role_has_permissions.role_id', 'model_has_roles.role_id')
      ->join('permissions', 'permissions.id', 'role_has_permissions.permission_id')
      ->select(
        'email'
      );

    if (!empty($instalacionId)) {
      $query = $query->where('permissions.name', $permission);
      $query = $query->where('users.instalacion_id', '=', $instalacionId);
    } else {
      $query = $query->where('permissions.name', $permission);
    }
    return $query->pluck('email');
  }
}
