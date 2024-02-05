<?php

namespace App\Repositories;

use App\Models\Traza;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class TrazaRepository
 * @package App\Repositories
 * @version March 20, 2020, 6:16 pm UTC
*/

class TrazaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ip',
        'accion_id',
        'usuario_id',
        'modelo',
        'descripcion'
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
        return Traza::class;
    }
    public function getTrazaTotal()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }
    public function getAllPaginateUser($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        $callBackNombre = EventsUtil::callBack('username', 'like', '%' . $filter . '%');
        $callBackAccion= EventsUtil::callBack('accion', 'like', '%' . $filter . '%');
        return $this->model->with([
            'usuario:id,username,email,activo',
            'usuarioSa:id,nombre,email,activo',
            'accion:id,accion'
        ])
            ->where('descripcion', 'like', '%' . $filter . '%')
            ->where('modelo', 'like', '%' . $filter . '%')
            ->orWhere('ip', 'like', '%' . $filter . '%')
            ->orWhere('created_at', 'like', '%' . $filter . '%')
            ->orWhereHas('usuario', $callBackNombre)
            ->orWhereHas('usuarioSa', $callBack)
            ->orWhereHas('accion', $callBackAccion)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
}
