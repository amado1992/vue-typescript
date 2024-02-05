<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\MTCostoCalidadReport;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;

/**
 * Class MTCostoCalidadReportRepository
 * @package App\Repositories
 * @version Agosto 16, 2021, 06:08 am CDT
 */
class MTCostoCalidadReportRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'nombre',
        'fecha',
        'ventaNetaImporte',
        'ventaNetaAcumulada',
        'instalacion_id'
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
        return MTCostoCalidadReport::class;
    }

    public function listCostoCalidadReport($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'instalacion:id,nombre',
        ])
            ->where('fecha', 'like', '%' . $filter . '%')
            ->where('ventaNetaImporte', 'like', '%' . $filter . '%')
            ->where('ventaNetaAcumulada', 'like', '%' . $filter . '%')
            ->orWhereHas('instalacion', $callBack)
            ->orderByDesc('fecha')
            ->paginate($request['itemsPerPage']);
    }

    /** DASHBOARD */
    public function totalReportesXanno($request)
    {
        return DB::table('mtcostocalidadreportes')
            ->selectRaw("year(fecha) name")//year
            ->selectRaw("count(id) y")//total_reportes
            ->where('instalacion_id', $request['instalacion_id'])
            ->limit(5)
            ->groupByRaw('year(fecha)')
            ->orderBy('fecha','asc')
            ->get();
    }
}
