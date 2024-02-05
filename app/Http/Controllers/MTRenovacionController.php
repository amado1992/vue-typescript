<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTRenovacionResource;
use App\Models\MTRenovacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTRenovacionController extends Controller
{
    public function  renew_process($id){
        //$seguimientos_process = MTSeguimientoTurismoMasHS::where('proceso_id', $id)->get();
        $lists = MTRenovacion::with('proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia')->where('proceso_id', $id)->get();
        return $lists;
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

            'proceso_id' => 'required',

        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }

        $model = new MTRenovacion();

        $model->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $model->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;
        $model->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        $model->fechaRenovar = $request->fechaRenovar;
        $model->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        $model->requisitoIncumplido = $request->requisitoIncumplido;
        $model->proceso_id = $request->proceso_id;
        $model->observacion = $request->observacion;
        if ($model->save()){
            return new MTRenovacionResource($model);
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
        $model = MTRenovacion::findOrFail($id);
        return new MTRenovacionResource($model);
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
        $model = MTRenovacion::findOrFail($id);

        $model->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $model->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;
        $model->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        $model->fechaRenovar = $request->fechaRenovar;
        $model->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        $model->requisitoIncumplido = $request->requisitoIncumplido;
        $model->proceso_id = $request->proceso_id;
        $model->observacion = $request->observacion;
        if ($model->save()){
            return new MTRenovacionResource($model);
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
        $model = MTRenovacion::findOrFail($id);
        if ($model->delete()){
            return new MTRenovacionResource($model);
        }
    }
}
