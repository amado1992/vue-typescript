<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTSistemaTecnologicoResource;
use App\Models\MTInstalacion;
use App\Models\MTOsde;
use App\Models\MTSistemaTecnologico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use \stdClass;


class MTSistemaTecnologicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sistemas_tecnologicos = MTSistemaTecnologico::with(['equipo.sistema', 'instalacion'])->paginate(100);

        return MTSistemaTecnologicoResource::collection($sistemas_tecnologicos);
    }

    public function instalaciones()
    {
        $lists = MTInstalacion::paginate(100);

        return $lists;
    }

    public function osdes()
    {
        $lists = MTOsde::paginate(100);

        return $lists;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'equipo_id' => 'required',
        'esContratadoMantenimiento' => 'required',//boolean
        'totalEqInstalado' => 'required',//integer
        'totalHrTrabajarEqInst' => 'required',//calculate double
        'totalHrDejadaTrabjEqInst' => 'required',//calculate double
        'coeficienteDispTecnica' => 'required',//calculate double
        /*'mantenimientoReparacion' => 'required',//integer
        'reparacionCapital' => 'required',//integer
        'reposicion' => 'required',//integer
        'observacion' => 'required',*/
        'instalacion_id' => 'required',
        'fechaReporte' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $sistema_tecnologico = new MTSistemaTecnologico();

        $sistema_tecnologico->equipo_id = $request->equipo_id;
        $sistema_tecnologico->esContratadoMantenimiento = $request->esContratadoMantenimiento;//boolean
        $sistema_tecnologico->totalEqInstalado = $request->totalEqInstalado;//integer

        $sistema_tecnologico->totalHrTrabajarEqInst = $request->totalHrTrabajarEqInst;//calculate double
        $sistema_tecnologico->totalHrDejadaTrabjEqInst = $request->totalHrDejadaTrabjEqInst;//calculate double
        $sistema_tecnologico->coeficienteDispTecnica = $request->coeficienteDispTecnica;//calculate double

        $sistema_tecnologico->mantenimientoReparacion= $request->mantenimientoReparacion;//integer
        $sistema_tecnologico->reparacionCapital = $request->reparacionCapital;//integer
        $sistema_tecnologico->reposicion = $request->reposicion;//integer

        $sistema_tecnologico->observacion = $request->observacion;
        $sistema_tecnologico->instalacion_id = $request->instalacion_id;

        $sistema_tecnologico->fechaReporte = $request->fechaReporte;

        if ($sistema_tecnologico->save()){
            return new MTSistemaTecnologicoResource($sistema_tecnologico);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sistema_tecnologico = MTSistemaTecnologico::findOrFail($id);
        return new MTSistemaTecnologicoResource($sistema_tecnologico);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sistema_tecnologico = MTSistemaTecnologico::findOrFail($id);

        $sistema_tecnologico->equipo_id = $request->equipo_id;
        $sistema_tecnologico->esContratadoMantenimiento = $request->esContratadoMantenimiento;//boolean
        $sistema_tecnologico->totalEqInstalado = $request->totalEqInstalado;//integer

        $sistema_tecnologico->totalHrTrabajarEqInst = $request->totalHrTrabajarEqInst;//calculate double
        $sistema_tecnologico->totalHrDejadaTrabjEqInst = $request->totalHrDejadaTrabjEqInst;//calculate double
        $sistema_tecnologico->coeficienteDispTecnica = $request->coeficienteDispTecnica;//calculate double

        $sistema_tecnologico->mantenimientoReparacion= $request->mantenimientoReparacion;//integer
        $sistema_tecnologico->reparacionCapital = $request->reparacionCapital;//integer
        $sistema_tecnologico->reposicion = $request->reposicion;//integer

        $sistema_tecnologico->observacion = $request->observacion;
        $sistema_tecnologico->instalacion_id = $request->instalacion_id;

        $sistema_tecnologico->fechaReporte = $request->fechaReporte;

        if ($sistema_tecnologico->save()){
            return new MTSistemaTecnologicoResource($sistema_tecnologico);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sistema_tecnologico = MTSistemaTecnologico::findOrFail($id);
        if ($sistema_tecnologico->delete()){
            return new MTSistemaTecnologicoResource($sistema_tecnologico);
        }
    }
        // Dashboard
    public function totalSistemasMantenimiento(Request $request)
    {
        return DB::table('mtsistemastecnologicos')
            ->selectRaw('year(fechaReporte) as name')// year
            ->selectRaw("count(case when mtsistemastecnologicos.esContratadoMantenimiento = 1 then 1 end) as y")//total_contratado
            ->where('instalacion_id', $request['instalacion_id'])
            ->groupByRaw('year(fechaReporte)')
            ->get();
    }
}
