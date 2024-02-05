<?php

namespace App\Repositories;

use App\Models\MTPropuestaLider;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTPropuestaLiderRepository
 * @package App\Repositories
 * @version June 10, 2021, 9:35 am CDT
 */

class MTPropuestaLiderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre',
        'apellido',
        'cargo',
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
        return MTPropuestaLider::class;
    }

    public function getAllPaginatePropuesta($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'instalacion:id,nombre',
            'sector:id,nombre',
            'osde:id,nombre',
            'provincia:id,nombre'
        ])
            ->where('nombre', 'like', '%' . $filter . '%')
            ->where('apellido', 'like', '%' . $filter . '%')
            ->where('cargo', 'like', '%' . $filter . '%')
            ->where('argumentacion', 'like', '%' . $filter . '%')
            ->orWhereHas('instalacion', $callBack)
            ->orWhereHas('sector', $callBack)
            ->orWhereHas('provincia', $callBack)
            ->orWhereHas('osde', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }
    public function getPropuestasEstado($request)
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

    public function getPropuestas()
    {
        return DB::table($this->model->getTable())->orderByDesc('created_at')->get();
    }

    public function getListadoRelaciones($request)
    {
        $query = $this->model->with([
            'instalacion:id,nombre',
            'sector:id,nombre',
            'osde:id,nombre',
            'provincia:id,nombre'
        ]);
        if (!empty($request['datos']['sector_id'])) {
            $query = $query->where('sector_id', '=', $request['datos']['sector_id']);
        }
        if (!empty($request['datos']['osde_id'])) {
            $query = $query->where('osde_id', '=', $request['datos']['osde_id']);
        }
        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getCantPropuestasXEstado($request)
    {
        return DB::table('mtpropuestalider')
            ->selectRaw("estado as name") //Estado
            ->selectRaw("count(estado) as y") //Total
            ->where('instalacion_id', $request['instalacion_id'])
            ->groupByRaw('estado')
            ->get();
    }
    public function getCantPropuestasXSector($request)
    {
        return DB::table('mtpropuestalider')
            ->join('mtsector', 'mtsector.id', '=', 'mtpropuestalider.sector_id')
            ->selectRaw("mtsector.nombre as name") //Sector
            ->selectRaw("count(estado) as y") //Total
            ->where('instalacion_id', $request['instalacion_id'])
            ->groupByRaw('mtsector.nombre')
            ->get();
    }
}
