<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTSeguimientoAlcanceTurismoMasHSResource;
use App\Models\MTSeguimientoAlcanceTurismoMasHS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTSeguimientoAlcanceTurismoMasHSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTSeguimientoAlcanceTurismoMasHS::with(['alcance_turismo_mas_higienico_seguro.instalacion.municipio.provincia',
                                                         'alcance_turismo_mas_higienico_seguro.proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia'])->paginate(100);

        return MTSeguimientoAlcanceTurismoMasHSResource::collection($lists);
    }

    public function  seguimientos_alcance($id){
        $lists = MTSeguimientoAlcanceTurismoMasHS::with(['alcance_turismo_mas_higienico_seguro.instalacion.municipio.provincia',
            'alcance_turismo_mas_higienico_seguro.proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia'])->where('mtalcance_id', $id)->get();
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

            'mtalcance_id' => 'required',

        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }

        $seguimiento = new MTSeguimientoAlcanceTurismoMasHS();
        $seguimiento->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $seguimiento->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        $seguimiento->fechaSeguimientoMensual = $request->fechaSeguimientoMensual;
        $seguimiento->fechaSeguimientoSemestral = $request->fechaSeguimientoSemestral;
        $seguimiento->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        $seguimiento->fechaRetiroSuspensionTemporalCertificado = $request->fechaRetiroSuspensionTemporalCertificado;
        $seguimiento->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        $seguimiento->requisitoIncumplido = $request->requisitoIncumplido;
        $seguimiento->mtalcance_id = $request->mtalcance_id;
        if ($seguimiento->save()){
            return new MTSeguimientoAlcanceTurismoMasHSResource($seguimiento);
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
        $seguimiento = MTSeguimientoAlcanceTurismoMasHS::findOrFail($id);
        return new MTSeguimientoAlcanceTurismoMasHSResource($seguimiento);
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
        $seguimiento = MTSeguimientoAlcanceTurismoMasHS::findOrFail($id);
        $seguimiento->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $seguimiento->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        $seguimiento->fechaSeguimientoMensual = $request->fechaSeguimientoMensual;
        $seguimiento->fechaSeguimientoSemestral = $request->fechaSeguimientoSemestral;
        $seguimiento->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        $seguimiento->fechaRetiroSuspensionTemporalCertificado = $request->fechaRetiroSuspensionTemporalCertificado;
        $seguimiento->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        $seguimiento->requisitoIncumplido = $request->requisitoIncumplido;
        $seguimiento->mtalcance_id = $request->mtalcance_id;
        if ($seguimiento->save()){
            return new MTSeguimientoAlcanceTurismoMasHSResource($seguimiento);
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
        $seguimiento = MTSeguimientoAlcanceTurismoMasHS::findOrFail($id);
        if ($seguimiento->delete()){
            return new MTSeguimientoAlcanceTurismoMasHSResource($seguimiento);
        }
    }
}
