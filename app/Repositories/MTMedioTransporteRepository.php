<?php

namespace App\Repositories;

use App\Models\MTMedioTransporte;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Class MTMedioTransporteRepository
 * @package App\Repositories
 */
class MTMedioTransporteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'marca',
        'modelo',
        'color',
        'electrico',
        'electrico',
        'num_motor',
        'matricula',
        'num_carroceria',
        'anno_fabricacion',
        'edad',
        'capacidad_carga',
        'neumaticos',
        'indice_consumo',
        'combustible_asignado',
        'fecha_ficav',
        'asignado_cargo',
        'asignado_nombre_apellidos',
        'folio',
        'moto_horas',
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
        return MTMedioTransporte::class;
    }

    /**
     * Return an array of MTMedioTransporte with pagination.
     **/
    public function listMedioTransporte_paginate($request)
    {
        return 'Hello repository ;)';
    }

    /**
     * Return an array of MTMedioTransporte.
     **/
    public function listMedioTransporte_($instalacionId)
    {
        return $this->model->with([
            'instalaciones:id,nombre,provincia_id,osde_id',
            'instalaciones.provincia:id,nombre',
            'instalaciones.osdes:id,nombre',
        ])
            ->where('instalacion_id', $instalacionId)
            ->get();
    }

    public function showReportCDC($instalacionId, $request)
    {
        $query = DB::table('mtmedio_transportes')
            ->join('historico_vehiculos', 'mtmedio_transportes.id', '=', 'historico_vehiculos.vehiculo_id')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
            ->select(
                'mtosde.nombre as osde',
                'mtinstalacion.nombre as instalacion',
                'mtprovincia.nombre as provincia',
                'tipo_medio_transporte',
                'tipo_flota',
            )
            ->selectRaw("count(case when situacion_actual = 'Trabajando' or situacion_actual = 'Alta' and motivo_paralizado = 'ATM' and motivo_paralizado = 'Taller' and motivo_paralizado = ' Otros' and situacion_actual = 'Propuesto a Baja'  then 1 end) as cantidad")
            ->selectRaw("count(case when situacion_actual = 'Trabajando' or situacion_actual = 'Alta' and fecha = ? then 1 end) as trabajando", [$request['fecha_fin']])
            ->selectRaw("count(case when motivo_paralizado = 'ATM' and fecha = ? then 1 end) as atm", [$request['fecha_fin']])
            ->selectRaw("count(case when motivo_paralizado = 'Taller' and fecha = ? then 1 end) as taller", [$request['fecha_fin']])
            ->selectRaw("count(case when motivo_paralizado = ' Máquina' or motivo_paralizado = 'Motor' and fecha = ? then 1 end) as motor", [$request['fecha_fin']])
            ->selectRaw("count(case when motivo_paralizado = ' Otros' and fecha = ? then 1 end) as otros", [$request['fecha_fin']])
            ->selectRaw("count(case when situacion_actual = 'Propuesto a Baja' and fecha = ? then 1 end) as pendiente_baja", [$request['fecha_fin']])
            ->selectRaw("count(case when historico_vehiculos.estado = 'Baja Técnica' and (historico_vehiculos.estado_fechaInicial = ? and historico_vehiculos.estado_fechaFinal = ?) then 1 end) as baja_tecnica", [$request['fecha_inicio'], $request['fecha_fin']])
            ->selectRaw("count(case when historico_vehiculos.estado = 'Baja Turística' and (historico_vehiculos.estado_fechaInicial = ? and historico_vehiculos.estado_fechaFinal = ?) then 1 end) as baja_turistica", [$request['fecha_inicio'], $request['fecha_fin']])
            //->selectRaw("round(count(case when (situacion_actual = 'Trabajando' or situacion_actual = 'Alta') then 1 end) / count(case when (situacion_actual = 'Trabajando' or situacion_actual = 'Alta') then 1 end) + count(case when motivo_paralizado = 'ATM' then 1 end) + count(case when motivo_paralizado = 'Taller' then 1 end) + count(case when motivo_paralizado = ' Máquina' or motivo_paralizado = 'Motor' then 1 end) + count(case when motivo_paralizado = ' Otros' then 1 end) + count(case when situacion_actual = 'Propuesto a Baja' then 1 end)) as coeficiente_disposicion_tecnica")
            ->selectRaw("round(count(case when (situacion_actual = 'Trabajando' or situacion_actual = 'Alta') then 1 end) / count(situacion_actual)) as coeficiente_disposicion_tecnica")
            ->where('tipo_flota', $request['tipo_flota'])
            //->whereRaw('month(fecha) = month(?)', $request['fecha_fin'])
            ->groupBy('tipo_medio_transporte');

        #region - Filtros opcionales

        /*         if (!empty($request['fecha_inicio']) && !empty($request['fecha_fin'])) {
            $fecha_inicio = $request['fecha_inicio'];
            $fecha_fin = $request['fecha_fin'];
            $query = $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
        } else {
            $query = $query->whereMonth('fecha', '=', date('m'));
        } */

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

    public function showReportParqueTotal($instalacionId)
    {
        $cantTipoFlota = $this->countTipoFlotas($instalacionId);
        $query = DB::table('mtmedio_transportes')
            ->selectRaw("count(situacion_actual) as cantidad")
            ->selectRaw("count(case when situacion_actual = 'Trabajando' or situacion_actual = 'Alta' then 1 end) as trabajando")
            ->selectRaw("count(case when motivo_paralizado = 'ATM' then 1 end) as atm")
            ->selectRaw("count(case when motivo_paralizado = 'Taller' then 1 end) as taller")
            ->selectRaw("count(case when motivo_paralizado = ' Máquina' or motivo_paralizado = 'Motor' then 1 end) as motor")
            ->selectRaw("count(case when motivo_paralizado = ' Otros' then 1 end) as otros")
            ->selectRaw("count(case when situacion_actual = 'Propuesto a Baja' then 1 end) as pendiente_baja")
            ->selectRaw("count(case when situacion_actual = 'Baja Técnica' then 1 end) as baja_tecnica")
            ->selectRaw("round(count(case when (situacion_actual = 'Trabajando' or situacion_actual = 'Alta') then 1 end) / count(situacion_actual)) as coeficiente_disposicion_tecnica")
            ->get();

        if ($cantTipoFlota > 1) {
            return $query;
        } else {
            return array();
        }
    }
    public function showFichaMediosTransporte($request)
    {
        $query = DB::table('mtmedio_transportes')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
            ->select(
                'mtosde.nombre as osde',
                'mtinstalacion.nombre as instalacion',
                'mtprovincia.nombre as provincia',
                'instalacion_id',
                'fecha',
                'tipo_flota',
                'tipo_medio_transporte',
                'marca',
                'modelo',
                'color',
                'estado_tecnico',
                'electrico',
                'tipo_combustible',
                'num_motor',
                'situacion_actual',
                'motivo_paralizado',
                'clase',
                'matricula',
                'num_carroceria',
                'anno_fabricacion',
                'edad',
                'capacidad_carga',
                'neumaticos',
                'indice_consumo',
                'combustible_asignado',
                'fecha_ficav',
                'asignado_cargo',
                'asignado_nombre_apellidos',
//                'folio',
                'ubicacion_motor',
                'moto_horas'
            );
        #region Filtros
        if (!empty($request['instalacion_id'])) {
            $query = $query->where('instalacion_id', [$request['instalacion_id']]);
        }

        if (!empty($request['osde'])) {
            $query = $query->where('mtosde.nombre', [$request['osde']]);
        }

        if (!empty($request['provincia'])) {
            $query = $query->where('mtprovincia.nombre', [$request['provincia']]);
        }

        if (!empty($request['up_mintur'])) { // Unidad Presupuestada
            $query = $query->where('up_mintur', [$request['up_mintur']]);
        }

        if (!empty($request['delegaciones'])) {
            $query = $query->where('delegaciones', [$request['delegaciones']]);
        }

        if (!empty($request['tipo_medio_transporte'])) {
            $query = $query->where('tipo_medio_transporte', $request['tipo_medio_transporte']);
        }

        if (!empty($request['tipo_flota'])) {
            $query = $query->where('tipo_flota', [$request['tipo_flota']]);
        }

        if (!empty($request['estado_tecnico'])) {
            $query = $query->where('estado_tecnico', [$request['estado_tecnico']]);
        }

        if (!empty($request['situacion_actual'])) {
            $query = $query->where('situacion_actual', [$request['situacion_actual']]);
        }

        if (!empty($request['tipo_combustible'])) {
            $query = $query->where('tipo_combustible', [$request['tipo_combustible']]);
        }

        if (!empty($request['clase'])) {
            $query = $query->where('clase', [$request['clase']]);
        }

        if (!empty($request['ubicacion_motor'])) {
            $query = $query->where('ubicacion_motor', [$request['ubicacion_motor']]);
        }
        #endregion
        return $query->get();
    }

    public function ultimaFila($arreglo, $totalName)
    {
        $lastRow = new stdClass();
        $lastRow->cantidad = 0;
        $lastRow->trabajando = 0;
        $lastRow->atm = 0;
        $lastRow->taller = 0;
        $lastRow->motor = 0;
        $lastRow->otros = 0;
        $lastRow->pendiente_baja = 0;
        $lastRow->baja_tecnica = 0;
        $lastRow->baja_turistica = 0;
        $lastRow->coeficiente_disposicion_tecnica = 0;
        foreach ($arreglo as $a) {
            $lastRow->tipo_medio_transporte = $totalName;
            $lastRow->cantidad += $a->cantidad;
            $lastRow->trabajando += $a->trabajando;
            $lastRow->atm += $a->atm;
            $lastRow->taller += $a->taller;
            $lastRow->motor += $a->motor;
            $lastRow->otros += $a->otros;
            $lastRow->pendiente_baja += $a->pendiente_baja;
            $lastRow->baja_tecnica += $a->baja_tecnica;
            $lastRow->baja_turistica += $a->baja_turistica;
            $lastRow->coeficiente_disposicion_tecnica += $a->coeficiente_disposicion_tecnica;
        }
        $arreglo[count($arreglo)] = $lastRow;
        return $arreglo;
    }

    public function getTipoFlotas($instalacionId)
    {
        return $this->model
            ->select('tipo_flota')
            ->groupBy('tipo_flota')
            ->get();
    }
    /**
     * Devuelve la cant. de tipos de flota
     * @return integer
     **/
    public function countTipoFlotas($instalacionId = null)
    {
        $query = $this->model
            ->groupBy('tipo_flota');

        if (!empty($instalacionId)) {
            $query = $query->select('tipo_flota', 'instalacion_id');
            $query = $query->where('instalacion_id', $instalacionId);
        } else {
            $query = $query->select('tipo_flota');
        }
        return $query->get()->count();
    }

    public function coeficienteDisposicionTec()
    {
        $osdes = DB::table('mtmedio_transportes')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre as osde')
            // ->selectRaw("COUNT(CASE WHEN situacion_actual = 'Trabajando' THEN 1 END) AS trabajando")
            // ->selectRaw("COUNT(CASE WHEN situacion_actual = 'Propuesto a Baja' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'ATM' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'Taller' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'Motor' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'Otros' THEN 1 END) AS paralizado")
            // ->selectRaw("COUNT(CASE WHEN situacion_actual = 'Baja Técnica' THEN 1 END) AS baja_tecnica")
            ->groupBy('mtosde.nombre')
            ->pluck('osde');

        $trabajando = DB::table('mtmedio_transportes')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre as osde')
            ->selectRaw("COUNT(CASE WHEN situacion_actual = 'Trabajando' THEN 1 END) AS trabajando")
            ->groupBy('mtosde.nombre')
            ->pluck('trabajando');

        $paralizado = DB::table('mtmedio_transportes')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre as osde')
            ->selectRaw("COUNT(CASE WHEN situacion_actual = 'Propuesto a Baja' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'ATM' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'Taller' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'Motor' THEN 1 END) + COUNT(CASE WHEN motivo_paralizado = 'Otros' THEN 1 END) AS paralizado")
            ->groupBy('mtosde.nombre')
            ->pluck('paralizado');

        $baja_tecnica = DB::table('mtmedio_transportes')
            ->join('mtinstalacion', 'instalacion_id', '=', 'mtinstalacion.id')
            ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre as osde')
            ->selectRaw("COUNT(CASE WHEN situacion_actual = 'Baja Técnica' THEN 1 END) AS baja_tecnica")
            ->groupBy('mtosde.nombre')
            ->pluck('baja_tecnica');

        $data[0] = new stdClass();
        $data[0]->name = 'Trabajando';
        $data[0]->data = $trabajando;
        $data[1] = new stdClass();
        $data[1]->name = 'Paralizado';
        $data[1]->data = $paralizado;
        $data[2] = new stdClass();
        $data[2]->name = 'Baja Técnica';
        $data[2]->data = $baja_tecnica;

        $data2 = array();
        $data2['osdes'] = $osdes;

        $merge = array();
        array_push($merge, $osdes, $data);
        return $merge;
    }

    public function coeficienteDisposicionTecTotal()
    {
        $total = DB::table('mtmedio_transportes')
            ->count();

        $trabajando = DB::table('mtmedio_transportes')
            ->where('situacion_actual', 'Trabajando')
            ->count();

        $atm = DB::table('mtmedio_transportes')
            ->where('motivo_paralizado', 'ATM')
            ->count();

        $motor = DB::table('mtmedio_transportes')
            ->where('motivo_paralizado', 'Motor')
            ->orWhere('motivo_paralizado', 'Máquina')
            ->count();

        $otros = DB::table('mtmedio_transportes')
            ->where('motivo_paralizado', 'Otros')
            ->count();

        $pendiente_baja = DB::table('mtmedio_transportes')
            ->where('situacion_actual', 'Propuesto a Baja')
            ->count();

        $data[0] = new stdClass();
        $data[0]->name = 'Trabajando';
        $data[0]->y = $trabajando * 100 / $total;
        $data[1] = new stdClass();
        $data[1]->name = 'ATM';
        $data[1]->y = $atm * 100 / $total;
        $data[2] = new stdClass();
        $data[2]->name = 'Motor';
        $data[2]->y = $motor * 100 / $total;
        $data[3] = new stdClass();
        $data[3]->name = 'Otros';
        $data[3]->y = $otros * 100 / $total;
        $data[4] = new stdClass();
        $data[4]->name = 'P/B';
        $data[4]->y = $pendiente_baja * 100 / $total;


        return $data;
    }

    public function listaVehiculosProximoVencimientoFICAV()
    {
        $total = DB::table('mtmedio_transportes')->get();

        if ($total->isNotEmpty()) {
            foreach ($total as $key => $value) {
                $date = Carbon::parse($value->fecha_ficav);
                $now = now();
                $response[$key] = new stdClass();
                if ($date->diffInMonths($now) === 2) {
                    $response[$key] = $value;
                    $response[$key]->status = 'vencido';
                } else {
                    $response[$key] = $value;
                    $response[$key]->status = 'activo';
                }
            }
            return $response;
        } else {
            $response[] = new stdClass();
            $response[0]->status = 'clean';
            return $response;
        }
    }
}
