<?php

namespace App\Repositories;

use App\Models\Usuario;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use zun\sa\Client;

/**
 * Class UsuarioRepository
 * @package App\Repositories
 * @version January 8, 2021, 2:10 pm CST
 */
class UsuarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'nombre',
        'apellidos',
        'email',
        'admin',
        'activo'
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
        return Usuario::class;
    }

    public function getAllPaginateUser($request)
    {
        DB::statement('ALTER TABLE user_sa AUTO_INCREMENT = 10');
        $client = new Client(env('SA_API'));
//        $client = new Client('http://sa.test.dev.get.tur.cu');
        try {
            $rvadmin = $client->session->login(env('SA_ADMIN_USER'), env('SA_ADMIN_PASSWORD'));
//            $rvadmin = $client->session->login('rvadmin', 'rvadminPrueba*2020');
        } catch (\Throwable $e) {
            if ($e->getCode() === 0) {
                return response(array('success' => false, 'message' => 'No hay conexion con el servidor de usuarios ZUNsa'));
            }
        }
        $module = env('SA_MODULE');
//        $module = '100';
        try {
            $users = $client->admin->getUsers($module);
        } catch (\Throwable $e) {
            if ($e->getCode() === 0) {
                return response(array('success' => false, 'message' => 'No hay conexion con el servidor de usuarios ZUNsa'));
            }
        }
        foreach ($users as $user) {
            $userFind = DB::table($this->model->getTable())->where('uuid', '=', $user->uuid)->get();
            if ($userFind->toArray() === []) {
                Usuario::create([
                    'uuid' => $user->uuid,
                    'nombre' => $user->nombre,
                    'apellidos' => $user->apellidos,
                    'email' => $user->email,
                    'admin' => $user->admin,
                    'activo' => $user->activo
                ]);
                $userFind = null;
            }
        }
        $filter = $request['search'];
        return $this->model->with([
            'rolesSa:model_has_roles.role_id,name'
        ])
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('apellidos', 'like', '%' . $filter . '%')
            ->where('email', 'like', '%' . $filter . '%')
            ->where('nombre', '!=', 'superuser')
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }

    public function findNombre($nombre)
    {
        return DB::table('user_sa')->where('nombre', '=', $nombre)->get();

    }

    public function getUsuarioSa($id)
    {
        return DB::table('user_sa')->where('id', '=', $id)->get();

    }

}
