<?php

namespace App\Repositories;

use App\Models\MTPlanRecape;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;
use Carbon\Carbon;


/**
 * Class MTPlanRecapeRepository
 * @package App\Repositories
 */
class MTPlanRecapeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'instalacion_id',
        'fecha',
        'potencial',
        'anno',
        'plan_recape',
        'observaciones',
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
        return MTPlanRecape::class;
    }

    /**
     * Return a list of MTPlanRecape with pagination.
     **/
    public function listPlanRecape_paginate($request)
    {
        $filter = $request['search'];
        $callBack = EventsUtil::callBack('nombre', 'like', '%' . $filter . '%');
        return $this->model->with([
            'instalaciones:id,nombre,provincia_id,osde_id',
            'instalaciones.provincia:id,nombre',
            'instalaciones.osdes:id,nombre',
        ])
            ->where('instalacion_id', $request['instalacion_id'])
            ->orWhereHas('instalaciones', $callBack)
            ->orWhereHas('instalaciones.osdes', $callBack)
            ->orWhereHas('instalaciones.provincia', $callBack)
            ->paginate($request['itemsPerPage']);
    }

    /**
     * Return a list of MTPlanRecape.
     **/
    public function listPlanRecape_($instalacionId)
    {
        $query = $this->model->with([
            'instalaciones:id,nombre,provincia_id,osde_id',
            'instalaciones.provincia:id,nombre',
            'instalaciones.osdes:id,nombre',
        ]);

        if (!empty($instalacionId)) {
            $query = $query->where('instalacion_id', '=', $instalacionId);
        }
        return $query->get();
    }
    public function planRecapeOsde_dashboard()
    {
        $osdes = DB::table('mtmedio_transportes')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre as osde')
            ->groupBy('mtosde.nombre')
            ->pluck('osde');

        $potencial = DB::table('mtplan_recape')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtactualizaciones_plan_recape', 'plan_id', '=', 'mtactualizaciones_plan_recape.id')
            ->selectRaw('SUM(mtplan_recape.potencial) as potencial')
            ->groupBy('mtosde.nombre')
            ->pluck('potencial')->toArray();

        $recapados = DB::table('mtplan_recape')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtactualizaciones_plan_recape', 'plan_id', '=', 'mtactualizaciones_plan_recape.id')
            ->selectRaw('SUM(mtactualizaciones_plan_recape.recapados) as recapados')
            ->groupBy('mtosde.nombre')
            ->pluck('recapados')->toArray();

        $data[] = array();
        $data[0]['name'] = 'Potencial';
        $data[0]['data'] = [intval($potencial)];
        $data[1] = array();
        $data[1]['name'] = 'Recapados';
        $data[1]['data'] = [intval($recapados)];

        $data2 = array();
        $data2['osdes'] = $osdes;

        $merge = array();
        array_push($merge, $osdes, $data);
        return $merge;
    }
    public function planRecapeTotal_dashboard()
    {
        $potencial = DB::table('mtplan_recape')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtactualizaciones_plan_recape', 'plan_id', '=', 'mtactualizaciones_plan_recape.id')
            ->selectRaw('SUM(mtplan_recape.potencial) as potencial')
            ->groupBy('mtosde.nombre')
            ->pluck('potencial')->toArray();

        $recapados = DB::table('mtplan_recape')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtactualizaciones_plan_recape', 'plan_id', '=', 'mtactualizaciones_plan_recape.id')
            ->selectRaw('SUM(mtactualizaciones_plan_recape.recapados) as recapados')
            ->groupBy('mtosde.nombre')
            ->pluck('recapados')->toArray();

        $data[] = array();
        $data[0]['name'] = 'Potencial';
        $data[0]['y'] = intval($potencial);
        $data[1] = array();
        $data[1]['name'] = 'Recapados';
        $data[1]['y'] = intval($recapados);

        return $data;
    }
    /**
     * Devuelve un array con el id de instalación y el estado del plan de recape.
     * @return Array
     */
    public function comprobarExistenciaPlanRecape()
    {
        $response = ['status' => null, 'instlacion_id' => null];
        $year = now()->format('Y');

        $plan = DB::table('mtplan_recape')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->select(
                'mtplan_recape.fecha',
                'mtplan_recape.instalacion_id',
            )
            ->get();

        if ($plan->isEmpty()) {
            $response['status'] = 'vencido';
            return $response;
        } else {
            $response_date = Carbon::parse($plan->last()->fecha);
            if ($response_date->format('Y') < $year) {
                $instalacion = $plan->pluck('instalacion_id');
                $response['status'] = 'vencido';
                $response['instlacion_id'] = $instalacion[0];
                return $response;
            } else {
                $instalacion = $plan->pluck('instalacion_id');
                $response['status'] = 'activo';
                $response['instlacion_id'] = $instalacion[0];
                return $response;
            }
        }
    }
}
