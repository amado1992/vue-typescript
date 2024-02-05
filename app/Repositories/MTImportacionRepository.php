<?php


namespace App\Repositories;

use App\Models\MTImportacion;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTImportacionRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'mes',
        'anno',
        'plan',
        'real',
        'empresa_id',
        'indicador_id',
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

    public function model()
    {
        return MTImportacion::class;
    }

    public function listMTImportacion()
    {
        $year = date('Y');
        $mtImportacion  = $this->model->with(['empresa'])
            ->where('anno', $year)
            ->orderBy('mes', 'asc')
            ->get();

        if (!isset($mtImportacion) || $mtImportacion == null)
            return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay Importación que mostrar']);
        return $mtImportacion;
    }

    public function getAllPaginateImportacion($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('empresa_id', 'like', '%' . $filter . '%');
        return $this->model->with([
            'empresa'
        ])
            ->where('mes', 'like', '%' . $filter . '%')
            ->where('anno', 'like', '%' . $filter . '%')
            ->where('plan', 'like', '%' . $filter . '%')
            ->where('real', 'like', '%' . $filter . '%')
            ->orWhereHas('empresa', $callBack)
            ->orderByDesc('created_at')
            ->paginate($request['itemsPerPage']);
    }

    public function getImportacionIndicadorMes($request)
    {
        return $this->model->with([
            'empresa'
        ])
            ->whereBetween('mes', [$request['mes_start'], $request['mes_end']])
            ->where('indicador_id', $request['indicador_id'])
            ->paginate($request['itemsPerPage']);
    }

    public function getImportacionEmpresaMes($request)
    {
        return $this->model->with([
            'empresa'
        ])
            ->whereBetween('mes', [$request['mes_start'], $request['mes_end']])
            ->where('anno', date('Y'))
            ->where('empresa_id',  $request['empresa_id'])
            ->paginate($request['itemsPerPage']);
    }

    public function compararPlanImportaciones()
    {
        $year = now()->format('Y');
        $lastyear = now()->subYears(1)->format('Y');

        $query = DB::table('mtimportacions')
            ->join('mtindicadors', 'mtimportacions.indicador_id', '=', 'mtindicadors.id')
            ->join('mtrenglons', 'mtindicadors.renglon_id', '=', 'mtrenglons.id')
            ->select(
                'mtimportacions.anno AS year',
                'mtindicadors.nombre AS indicador',
                'mtrenglons.nombre AS renglon',
            )
            ->selectRaw("SUM(IF(mtimportacions.anno = ?,mtimportacions.plan,0)) AS total_plan_year", [$year])
            ->selectRaw("SUM(IF(mtimportacions.anno = ?,mtimportacions.real,0)) AS total_real_year", [$year])
            ->selectRaw("SUM(IF(mtimportacions.anno = ?,mtimportacions.plan,0)) AS total_plan_last_year", [$lastyear])
            ->selectRaw("SUM(IF(mtimportacions.anno = ?,mtimportacions.real,0)) AS total_real_last_year", [$lastyear])
            ->selectRaw("round(SUM(IF(mtimportacions.anno = ?,mtimportacions.real,0)) / SUM(IF(mtimportacions.anno = ?,mtimportacions.plan,0)) * 100,0) AS xciento_lastyear", [$lastyear, $lastyear])
            ->selectRaw("round(SUM(IF(mtimportacions.anno = ?,mtimportacions.real,0)) / SUM(IF(mtimportacions.anno = ?,mtimportacions.plan,0)) * 100,0) AS xciento_year", [$year, $year])
            ->whereBetween('mtimportacions.anno', [$lastyear, $year])
            ->groupBy(['mtrenglons.nombre', 'mtindicadors.nombre', 'mtimportacions.anno'])
            ->orderBy('mtimportacions.anno')
            ->get();

        if ($query->isNotEmpty()) {
            foreach ($query as $key => $value) {
                $response[$key] = new stdClass();
                if ($key === 0) {
                    $response[$key]->renglon = $value->renglon;
                    $response[$key]->indicador = $value->indicador;
                    $response[$key]->total_plan_year = $value->total_plan_year;
                    $response[$key]->total_real_year = $value->total_real_year;
                    $response[$key]->total_plan_last_year = $value->total_plan_last_year;
                    $response[$key]->total_real_last_year = $value->total_real_last_year;
                    $response[$key]->xciento_lastyear = $value->xciento_lastyear;
                    $response[$key]->xciento_year = $value->xciento_year;
                    $response[$key]->year = $value->year;
                } elseif ($value->renglon === $response[$key - 1]->renglon) {
                    $response[$key]->renglon = '';
                    $response[$key]->indicador = $value->indicador;
                    $response[$key]->total_plan_year = $value->total_plan_year;
                    $response[$key]->total_real_year = $value->total_real_year;
                    $response[$key]->total_plan_last_year = $value->total_plan_last_year;
                    $response[$key]->total_real_last_year = $value->total_real_last_year;
                    $response[$key]->xciento_lastyear = $value->xciento_lastyear;
                    $response[$key]->xciento_year = $value->xciento_year;
                    $response[$key]->year = $value->year;
                } else {
                    $response[$key]->renglon = $value->renglon;
                    $response[$key]->indicador = $value->indicador;
                    $response[$key]->total_plan_year = $value->total_plan_year;
                    $response[$key]->total_real_year = $value->total_real_year;
                    $response[$key]->total_plan_last_year = $value->total_plan_last_year;
                    $response[$key]->total_real_last_year = $value->total_real_last_year;
                    $response[$key]->xciento_lastyear = $value->xciento_lastyear;
                    $response[$key]->xciento_year = $value->xciento_year;
                    $response[$key]->year = $value->year;
                }
            }
            $ultimaFila = $this->ultimaFila($response, 'TOTAL');
            return $ultimaFila;
        } else {
            $response[] = new stdClass();
            return $response;
        }
    }

    public function ultimaFila($arreglo, $totalName)
    {
        $importacion = new stdClass();
        $importacion->total_plan_year = 0;
        $importacion->total_real_year = 0;
        $importacion->total_plan_last_year = 0;
        $importacion->total_real_last_year = 0;
        $importacion->xciento_lastyear = 0;
        $importacion->xciento_year = 0;
        foreach ($arreglo as $a) {
            $importacion->renglon = $totalName;
            $importacion->indicador = '';
            $importacion->total_plan_year += $a->total_plan_year;
            $importacion->total_real_year += $a->total_real_year;
            $importacion->total_plan_last_year += $a->total_plan_last_year;
            $importacion->total_real_last_year += $a->total_real_last_year;
            $importacion->xciento_lastyear += $a->xciento_lastyear;
            $importacion->xciento_year += $a->xciento_year;
        }
        $arreglo[count($arreglo)] = $importacion;
        return $arreglo;
    }

    // Dashboard
    public function getInfo($request)
    {
        $year = date('Y');
        $queryresult[] = new stdClass();

        $query0 = DB::table('mtimportacions')
            ->where('empresa_id', $request['instalacion_id'])
            ->where('anno', $year)
            ->get();
        foreach ($query0 as $element) {
            $queryresult[0]->name = $element->anno;
            $queryresult[0]->y = $query0->count();
        }
        return $queryresult;
    }
}
