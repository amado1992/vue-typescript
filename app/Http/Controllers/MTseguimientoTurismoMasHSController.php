<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTseguimientoTurismoMasHSResource;
use App\Models\MTSeguimientoTurismoMasHS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTseguimientoTurismoMasHSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function  seguimientos_process($id){
        //$seguimientos_process = MTSeguimientoTurismoMasHS::where('proceso_id', $id)->get();
        $seguimientos_process = MTSeguimientoTurismoMasHS::with('proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia')->where('proceso_id', $id)->get();
        return $seguimientos_process;
    }

    public function index()
    {
        $seguimientos = MTSeguimientoTurismoMasHS::with('proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia')->paginate(100);

        return MTSeguimientoTurismoMasHSResource::collection($seguimientos);
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

            'proceso_id' => 'required',

        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }

        $seguimiento = new MTSeguimientoTurismoMasHS();
        $seguimiento->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $seguimiento->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        $seguimiento->fechaSeguimientoMensual = $request->fechaSeguimientoMensual;
        $seguimiento->fechaSeguimientoTrimestral = $request->fechaSeguimientoTrimestral;
        $seguimiento->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        $seguimiento->fechaRetiroSuspensionTemporalCertificado = $request->fechaRetiroSuspensionTemporalCertificado;
        $seguimiento->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        $seguimiento->requisitoIncumplido = $request->requisitoIncumplido;
        $seguimiento->proceso_id = $request->proceso_id;
        if ($seguimiento->save()){
            return new MTseguimientoTurismoMasHSResource($seguimiento);
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
        $seguimiento = MTSeguimientoTurismoMasHS::findOrFail($id);
        return new MTseguimientoTurismoMasHSResource($seguimiento);
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
        $seguimiento = MTSeguimientoTurismoMasHS::findOrFail($id);
        $seguimiento->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $seguimiento->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        $seguimiento->fechaSeguimientoMensual = $request->fechaSeguimientoMensual;
        $seguimiento->fechaSeguimientoTrimestral = $request->fechaSeguimientoTrimestral;
        $seguimiento->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        $seguimiento->fechaRetiroSuspensionTemporalCertificado = $request->fechaRetiroSuspensionTemporalCertificado;
        $seguimiento->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        $seguimiento->requisitoIncumplido = $request->requisitoIncumplido;
        $seguimiento->proceso_id = $request->proceso_id;
        if ($seguimiento->save()){
            return new MTseguimientoTurismoMasHSResource($seguimiento);
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
        $seguimiento = MTSeguimientoTurismoMasHS::findOrFail($id);
        if ($seguimiento->delete()){
            return new MTseguimientoTurismoMasHSResource($seguimiento);
        }
    }
}
