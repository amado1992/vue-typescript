<?php

namespace App\Repositories;

use App\Models\MTActualizacionesPlanRecape;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Class MTActualizacionesPlanRecapeRepository
 * @package App\Repositories
 */
class MTActualizacionesPlanRecapeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'plan_id',
        'fecha_cumplimiento',
        'recapados',
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
        return MTActualizacionesPlanRecape::class;
    }

    /**
     * Return a list of MTActualizacionesPlanRecape with pagination.
     **/
    public function listActualizaciones_paginate()
    {
        return 'Hello repository ;)';
    }

    /**
     * Return a list of MTActualizacionesPlanRecape.
     **/
    public function listActualizaciones_($request)
    {
        return $this->model
            ->where('plan_id', $request['id'])
            ->get();
    }

    public function reporteCumplimientoPR($request)
    {
        $year = now()->year;
        $query = DB::table('mtactualizaciones_plan_recape')
            ->join('mtplan_recape', 'plan_id', '=', 'mtplan_recape.id')
            ->join('mtinstalacion', 'mtplan_recape.instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
            ->select(
                'mtosde.nombre as nombre_osde',
                'mtprovincia.nombre as provincia',

            )
            ->selectRaw("SUM(mtplan_recape.potencial) as potencial")
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS enero", [1, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS febrero", [2, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS marzo", [3, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS abril", [4, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS mayo", [5, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS junio", [6, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS julio", [7, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS agosto", [8, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS septiembre", [9, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS octubre", [10, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS noviembre", [11, $year])
            ->selectRaw("SUM(IF(month(fecha_cumplimiento) = ? && year(fecha_cumplimiento) = ?,recapados,0)) AS diciembre", [12, $year])
            ->selectRaw("SUM(mtplan_recape.plan_recape) as plan")
            ->selectRaw("SUM(recapados) as _real")
            ->selectRaw("ROUND(SUM(mtplan_recape.plan_recape)/SUM(recapados)*100) as porciento")
            ->groupBy('mtosde.nombre');

        #region - Filtros opcionales

        if (!empty($request['anno'])) {
            $query = $query->whereYear('fecha_cumplimiento', '=', $request['anno']);
        }

        if (!empty($request['instalacion_id'])) {
            $query = $query->where('instalacion_id', $request['instalacion_id']);
        }

        if (!empty($request['osde'])) {
            $query = $query->where('mtosde.nombre', $request['osde']);
        }

        if (!empty($request['provincia'])) {
            $query = $query->where('mtprovincia.nombre', $request['provincia']);
        }
        #endregion
        $reporte = $query->get();
        return $this->ultimaFila($reporte, 'TOTAL');
    }

    public function ultimaFila($arreglo, $totalName)
    {
        $lastRow = new stdClass();
        $lastRow->potencial = 0;
        $lastRow->enero = 0;
        $lastRow->febrero = 0;
        $lastRow->marzo = 0;
        $lastRow->abril = 0;
        $lastRow->mayo = 0;
        $lastRow->junio = 0;
        $lastRow->julio = 0;
        $lastRow->agosto = 0;
        $lastRow->septiembre = 0;
        $lastRow->octubre = 0;
        $lastRow->noviembre = 0;
        $lastRow->diciembre = 0;
        $lastRow->plan = 0;
        $lastRow->_real = 0;
        $lastRow->porciento = 0;
        foreach ($arreglo as $a) {
            $lastRow->nombre_osde = $totalName;
            $lastRow->potencial += $a->potencial;
            $lastRow->enero += $a->enero;
            $lastRow->febrero += $a->febrero;
            $lastRow->marzo += $a->marzo;
            $lastRow->abril += $a->abril;
            $lastRow->mayo += $a->mayo;
            $lastRow->junio += $a->junio;
            $lastRow->julio += $a->julio;
            $lastRow->agosto += $a->agosto;
            $lastRow->septiembre += $a->septiembre;
            $lastRow->octubre += $a->octubre;
            $lastRow->noviembre += $a->noviembre;
            $lastRow->diciembre += $a->diciembre;
            $lastRow->plan += $a->plan;
            $lastRow->_real += $a->_real;
            $lastRow->porciento = round($a->_real / $a->plan * 100, 0, PHP_ROUND_HALF_UP);
        }
        $arreglo[count($arreglo)] = $lastRow;
        return $arreglo;
    }
}
