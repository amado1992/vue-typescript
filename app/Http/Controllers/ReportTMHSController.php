<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportTMHSController extends Controller
{
    public function report_experts($id){
        $lists = DB::table('grupoevaluador')
            ->join('mtprocesosturismomashigienicoseguro', 'mtprocesosturismomashigienicoseguro.id', '=', 'grupoevaluador.proceso_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtprocesosturismomashigienicoseguro.instalacion_id')
            ->join('mtmunicipio', 'mtmunicipio.id', '=', 'mtinstalacion.municipio_id')
            ->join('mtfichaexpertos', 'mtfichaexpertos.id', '=', 'grupoevaluador.mtfichaexperto_id')
            ->join('mtoaces', 'mtoaces.id', '=', 'mtfichaexpertos.mtoace_id')
            ->join('mtentidadestmhs', 'mtentidadestmhs.id', '=', 'mtfichaexpertos.entidad_id')
            ->join('mtcategoriasdocentecientifica', 'mtcategoriasdocentecientifica.id', '=', 'mtfichaexpertos.mtcategdocentecientifica_id')

            ->select('mtfichaexpertos.nombreApellidos', 'mtoaces.nombre as oace', 'mtentidadestmhs.nombre as entidad',
                'mtinstalacion.nombre as instalacion', 'mtfichaexpertos.phone', 'mtfichaexpertos.email', 'mtfichaexpertos.annosExperienciaSectorLaboral',
                'mtfichaexpertos.cargoLaboral', 'mtfichaexpertos.tituloAcademico', 'mtcategoriasdocentecientifica.nombre as catc')
            ->where('mtmunicipio.id', '=', $id)
            ->get();
        return $lists;
    }

    public function report_register_base($id){
        $lists = DB::table('mtprocesosturismomashigienicoseguro')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtprocesosturismomashigienicoseguro.instalacion_id')
            ->join('mtmunicipio', 'mtmunicipio.id', '=', 'mtinstalacion.municipio_id')
            ->select('mtprocesosturismomashigienicoseguro.numeroRegistro', 'mtprocesosturismomashigienicoseguro.fechaEntregaMintur','mtinstalacion.nombre as instalacion')
            ->where('mtmunicipio.id', '=', $id)
            ->get();
        return $lists;
    }

}
