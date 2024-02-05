<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTReporteSistemaTecnologicoController extends Controller
{
    //1.4.1	RF Mostrar lista de sistemas en crisis donde el coeficiente de disponibilidad técnica (columna 9) sea <=60
    public function coefdisptec_menor_igual_sesenta(){
        $lists = DB::table('mtsistemastecnologicos')
            ->join('mtequipos', 'mtequipos.id', '=', 'mtsistemastecnologicos.equipo_id')
            ->join('mtsistemas', 'mtsistemas.id', '=', 'mtequipos.sistema_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtsistemastecnologicos.instalacion_id')
            ->select('mtsistemastecnologicos.coeficienteDispTecnica', 'mtsistemastecnologicos.fechaReporte',
            'mtinstalacion.nombre as instalacion', 'mtsistemas.nombre as sistema', 'mtequipos.nombre as equipo')
            ->where('mtsistemastecnologicos.coeficienteDispTecnica', '<=', 60)
            ->get();
        return $lists;
    }

//1.4.3	RF Mostrar listado de sistemas tecnológicos donde el coeficiente de disponibilidad técnica (columna L) sea menor o igual al 95
    public function coefdisptec_menor_igual_noventa_y_cinco(){
        $lists = DB::table('mtsistemastecnologicos')
            ->join('mtequipos', 'mtequipos.id', '=', 'mtsistemastecnologicos.equipo_id')
            ->join('mtsistemas', 'mtsistemas.id', '=', 'mtequipos.sistema_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtsistemastecnologicos.instalacion_id')
            ->select('mtsistemastecnologicos.coeficienteDispTecnica', 'mtsistemastecnologicos.fechaReporte',
                'mtinstalacion.nombre as instalacion', 'mtsistemas.nombre as sistema', 'mtequipos.nombre as equipo')
            ->where('mtsistemastecnologicos.coeficienteDispTecnica', '<=', 95)
            ->get();
        return $lists;
    }

//1.4.4	RF Mostrar listado de sistemas que no representan contrato de mantenimiento (columna 10 y 11)
    public function no_contrado_en_mantenimiento(){
        $lists = DB::table('mtsistemastecnologicos')
            ->join('mtequipos', 'mtequipos.id', '=', 'mtsistemastecnologicos.equipo_id')
            ->join('mtsistemas', 'mtsistemas.id', '=', 'mtequipos.sistema_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtsistemastecnologicos.instalacion_id')
            ->select('mtsistemastecnologicos.esContratadoMantenimiento', 'mtsistemastecnologicos.fechaReporte',
                'mtinstalacion.nombre as instalacion', 'mtsistemas.nombre as sistema', 'mtequipos.nombre as equipo')
            ->where('mtsistemastecnologicos.esContratadoMantenimiento', '=', false)
            ->get();
        return $lists;
    }

    public function porciento_sistemas_en_crisis(Request $request){
        $lists = DB::table('mtsistemastecnologicos')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtsistemastecnologicos.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtosde', 'mtosde.id', '=', 'mtentidad.osde_id')
            ->select(DB::raw('SUM(mtsistemastecnologicos.mantenimientoReparacion) * 100 / SUM(mtsistemastecnologicos.totalEqInstalado) as mantenimientoReparacion'),
                     DB::raw('SUM(mtsistemastecnologicos.reparacionCapital) * 100 / SUM(mtsistemastecnologicos.totalEqInstalado) as reparacionCapital'),
                     DB::raw('SUM(mtsistemastecnologicos.reposicion) * 100 / SUM(mtsistemastecnologicos.totalEqInstalado) as reposicion'))
            ->whereRaw('mtsistemastecnologicos.coeficienteDispTecnica <= 60 AND (mtosde.id = ? OR mtinstalacion.id = ?)', [$request->osde_id, $request->instalacion_id])
            ->get();
        return $lists;
    }

    public function coefdisptec_general(Request $request){
        $lists = DB::table('mtsistemastecnologicos')
            ->join('mtequipos', 'mtequipos.id', '=', 'mtsistemastecnologicos.equipo_id')
            ->join('mtsistemas', 'mtsistemas.id', '=', 'mtequipos.sistema_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtsistemastecnologicos.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtosde', 'mtosde.id', '=', 'mtentidad.osde_id')
            ->select('mtsistemastecnologicos.coeficienteDispTecnica', 'mtsistemastecnologicos.fechaReporte',
                'mtinstalacion.nombre as instalacion', 'mtsistemas.nombre as sistema', 'mtequipos.nombre as equipo')
            ->whereRaw('mtsistemastecnologicos.coeficienteDispTecnica <= ? AND (mtosde.id = ? OR mtinstalacion.id = ?)', [$request->coeficiente, $request->osde_id, $request->instalacion_id])
            ->get();
        return $lists;
    }

    public function no_contrado_en_mantenimiento_general(Request $request){
        $lists = DB::table('mtsistemastecnologicos')
            ->join('mtequipos', 'mtequipos.id', '=', 'mtsistemastecnologicos.equipo_id')
            ->join('mtsistemas', 'mtsistemas.id', '=', 'mtequipos.sistema_id')
            ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtsistemastecnologicos.instalacion_id')
            ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
            ->join('mtosde', 'mtosde.id', '=', 'mtentidad.osde_id')
            ->select('mtsistemastecnologicos.esContratadoMantenimiento', 'mtsistemastecnologicos.fechaReporte',
                'mtinstalacion.nombre as instalacion', 'mtsistemas.nombre as sistema', 'mtequipos.nombre as equipo')
            ->whereRaw('mtsistemastecnologicos.esContratadoMantenimiento = ? AND (mtosde.id = ? OR mtinstalacion.id = ?)', [$request->esContratadoMantenimiento, $request->osde_id, $request->instalacion_id])
            ->get();
        return $lists;
    }
}
