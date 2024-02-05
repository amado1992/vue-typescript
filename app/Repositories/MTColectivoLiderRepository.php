<?php

namespace App\Repositories;

use App\Models\MTColectivoLider;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTColectivoLiderRepository
 * @package App\Repositories
 * @version June 9, 2021, 1:40 pm CDT
 */
class MTColectivoLiderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sector_id',
        'provincia_id',
        'osde_id',
        'instalacion_id',
        'argumentacion',
        'estado'
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
        return MTColectivoLider::class;
    }

    public function getAllPaginateColectivo($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'instalacion:id,nombre',
            'sector:id,nombre',
            'osde:id,nombre',
            'provincia:id,nombre'
        ])
            ->where('argumentacion', 'like', '%' . $filter . '%')
            ->orWhereHas('instalacion', $callBack)
            ->orWhereHas('sector', $callBack)
            ->orWhereHas('provincia', $callBack)
            ->orWhereHas('osde', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }

    public function getColectivos()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }

    public function getColectivosEstado($request)
    {
        return $this->model->with([
            'instalacion:id,nombre',
            'sector:id,nombre',
            'osde:id,nombre',
            'provincia:id,nombre'
        ])
            ->where('estado', '=', $request['estado'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getListadoRelaciones($request)
    {
        $query = $this->model->with([
            'instalacion:id,nombre',
            'sector:id,nombre',
            'osde:id,nombre',
            'provincia:id,nombre'
        ]);
        if (!empty($request['datos']['sector_id'])){
            $query = $query->where('sector_id', '=' ,$request['datos']['sector_id']);
        }
        if (!empty($request['datos']['osde_id'])){
            $query = $query->where('osde_id','=', $request['datos']['osde_id']);
        }
        return $query->orderBy('created_at', 'desc')->get();
    }
}
