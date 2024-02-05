<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTDictamenResource;
use App\Models\MTDictamenTMHS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MTDictamenController extends Controller
{
    public function dictamen_tmhs(Request $request){
        $lists = MTDictamenTMHS::with(['proceso', 'proceso.instalacion', 'tipo_eval'])
                 ->where('proceso_id', $request->proceso_id)
                 ->where('seguimiento_id', null)
                 ->where('renovacion_id', null)
                 ->where('mttipoeval_id', '=', $request->mttipoeval_id)->get();
        return $lists;
    }

    public function dictamen_seguimiento(Request $request){
        $lists = MTDictamenTMHS::with(['proceso', 'proceso.instalacion', 'tipo_eval', 'seguimiento'])
            ->where('seguimiento_id', $request->seguimiento_id)
            ->where('mttipoeval_id', '=', $request->mttipoeval_id)->get();
        return $lists;
    }

    public function dictamen_renovacion(Request $request){
        $lists = MTDictamenTMHS::with(['proceso', 'proceso.instalacion', 'tipo_eval', 'renovacion'])
            ->where('renovacion_id', $request->renovacion_id)
            ->where('mttipoeval_id', '=', $request-> mttipoeval_id)->get();
        return $lists;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
       /* $validator = Validator::make($request->all(), [
            //'proceso_id' => 'required',
            /*'descripcion' => 'required',
            'aplicacionListaChequeo' => 'required',
            'puntuacionObtenida'=> 'required',
            'examenEscrito'=> 'required',*/

            /*'personalEvaluar' => 'required',
            'personalEvaluado'=> 'required',
            'aprobado'=> 'required',
            'suspenso'=> 'required',

            'puntuacionObtenidaSegundaEval',*/
       // ]);

       /* if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }*/
        $model = new MTDictamenTMHS();

        $model->proceso_id = $request->proceso_id;
        $model->seguimiento_id = $request->seguimiento_id;
        $model->renovacion_id = $request->renovacion_id;

        $model->descripcion = $request->descripcion;
        $model->aplicacionListaChequeo = $request->aplicacionListaChequeo;
        $model->puntuacionObtenida  = $request->puntuacionObtenida;
        $model->requisitoIncumplido = $request->requisitoIncumplido;

        $model->cantidadPuntosCritico  = $request->cantidadPuntosCritico;
        $model->observacion = $request->observacion;

        $model->examenEscrito  = $request->examenEscrito;
        $model->preguntaOralConocimiento = $request->preguntaOralConocimiento;

        $model->evaluacionInicial = $request->evaluacionInicial;
        $model->seguimientoSemestral = $request->seguimientoSemestral;
        $model->renovacion = $request->renovacion;

        $model->personalEvaluar = $request->personalEvaluar;
        $model->personalEvaluado = $request->personalEvaluado;

        if($request->personalEvaluar != 0 || $request->personalEvaluar != null){

            $model->porcientoPersonalEvaluado = $request->personalEvaluado * 100 / $request->personalEvaluar;
        }else {
        $model->porcientoPersonalEvaluado = 0;
    }

        $model->aprobado = $request->aprobado;
        $model->suspenso = $request->suspenso;

        if($request->personalEvaluado != 0 || $request->personalEvaluado != null){
        $model->porcientoAprobadoEvaluado = $request->aprobado * 100/$request->personalEvaluado;
        $model->porcientoSuspensoEvaluado = $request->suspenso * 100 / $request->personalEvaluado;
        }else {
            $model->porcientoAprobadoEvaluado = 0;
            $model->porcientoSuspensoEvaluado = 0;
        }

        $model->puntuacionObtenidaSegundaEval = $request->puntuacionObtenidaSegundaEval;
        $model->requisitoIncumplidoSegundaEval = $request->requisitoIncumplidoSegundaEval;
        $model->cantidadPuntosCriticoSegundaEval = $request->cantidadPuntosCriticoSegundaEval;
        $model->observacionSegundaEval = $request->observacionSegundaEval;

        $model->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $model->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        $model->otorgadoCertificado = $request->otorgadoCertificado;

        $model->mantenerCertificado = $request->mantenerCertificado;
        $model->suspenderTemporalCertificado = $request->suspenderTemporalCertificado;
        $model->cancelarCertificado = $request->cancelarCertificado;

        $model->renovarCertificado = $request->renovarCertificado;

        $model->elavoradoPor = $request->elavoradoPor;
        $model->nombreApellidos = $request->nombreApellidos;
        $model->fechaCierreDictamen = $request->fechaCierreDictamen;

        $model->aprobadoDelegadoTerritorialMintur = $request->aprobadoDelegadoTerritorialMintur;
        $model->nombreApellidosDelegadoTerritorio = $request->nombreApellidosDelegadoTerritorio;
        $model->fechaAprobacionDelegadoTerritorio = $request->fechaAprobacionDelegadoTerritorio;

        $model->aprobadoAutoridadadSanitariaTerritorio = $request->aprobadoAutoridadadSanitariaTerritorio;
        $model->nombreApellidosAutoridadadSanitariaTerritorio = $request->nombreApellidosAutoridadadSanitariaTerritorio;
        $model->fechaAprobacionAutoridadadSanitariaTerritorio = $request->fechaAprobacionAutoridadadSanitariaTerritorio;
        $model->mttipoeval_id = $request->mttipoeval_id;

        if ($model->save()){
            return new MTDictamenResource($model);
        }

       /* $validator = Validator::make($request->all(), [
            'proceso_id' => 'required',
            'descripcion' => 'required',
            'aplicacionListaChequeo' => 'required',
            'puntuacionObtenida'=> 'required',
            'examenEscrito'=> 'required',

            'personalEvaluar' => 'required',
            'personalEvaluado'=> 'required',
            'aprobado'=> 'required',
            'suspenso'=> 'required',

            'puntuacionObtenidaSegundaEval',
        ]);

        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }
        $model = MTProcesoTurismoMasHigienicoSeguro::findOrFail($request->proceso_id);

        $model->descripcion = $request->descripcion;
        $model->aplicacionListaChequeo = $request->aplicacionListaChequeo;
        $model->puntuacionObtenida  = $request->puntuacionObtenida;
        $model->requisitoIncumplidoPrimeraEval = $request->requisitoIncumplidoPrimeraEval;

        $model->cantidadPuntosCritico  = $request->cantidadPuntosCritico;
        $model->observacionPrimeraEval = $request->observacionPrimeraEval;

        $model->examenEscrito  = $request->examenEscrito;
        $model->preguntaOralConocimiento = $request->preguntaOralConocimiento;

        $model->evaluacionInicial = $request->evaluacionInicial;
        $model->seguimientoSemestral = $request->seguimientoSemestral;
        $model->renovacion = $request->renovacion;

        $model->personalEvaluar = $request->personalEvaluar;
        $model->personalEvaluado = $request->personalEvaluado;
        $model->porcientoPersonalEvaluado = $request->personalEvaluado * 100/$request->personalEvaluar;

        $model->aprobado = $request->aprobado;
        $model->porcientoAprobadoEvaluado = $request->aprobado * 100/$request->personalEvaluado;

        $model->suspenso = $request->suspenso;
        $model->porcientoSuspensoEvaluado = $request->suspenso * 100/$request->personalEvaluado;

        $model->puntuacionObtenidaSegundaEval = $request->puntuacionObtenidaSegundaEval;
        $model->requisitoIncumplidoSegundaEval = $request->requisitoIncumplidoSegundaEval;
        $model->cantidadPuntosCriticoSegundaEval = $request->cantidadPuntosCriticoSegundaEval;
        $model->observacionSegundaEval = $request->observacionSegundaEval;

        if ($model->save()){
            return new MTProcesoTurismoMasHigienicoSeguroResource($model);
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = MTDictamenTMHS::findOrFail($id);
        return new MTDictamenResource($model);
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
        $model = MTDictamenTMHS::findOrFail($id);

        $model->proceso_id  = $request->proceso_id;
        $model->seguimiento_id = $request->seguimiento_id;
        $model->renovacion_id = $request->renovacion_id;
        $model->descripcion = $request->descripcion;
        $model->aplicacionListaChequeo = $request->aplicacionListaChequeo;
        $model->puntuacionObtenida  = $request->puntuacionObtenida;
        $model->requisitoIncumplido = $request->requisitoIncumplido;

        $model->cantidadPuntosCritico  = $request->cantidadPuntosCritico;
        $model->observacion = $request->observacion;

        $model->examenEscrito  = $request->examenEscrito;
        $model->preguntaOralConocimiento = $request->preguntaOralConocimiento;

        $model->evaluacionInicial = $request->evaluacionInicial;
        $model->seguimientoSemestral = $request->seguimientoSemestral;
        $model->renovacion = $request->renovacion;

        $model->personalEvaluar = $request->personalEvaluar;
        $model->personalEvaluado = $request->personalEvaluado;

        if($request->personalEvaluar != 0 || $request->personalEvaluar != null){

            $model->porcientoPersonalEvaluado = $request->personalEvaluado * 100 / $request->personalEvaluar;
        }else {
            $model->porcientoPersonalEvaluado = 0;
        }

        $model->aprobado = $request->aprobado;
        $model->suspenso = $request->suspenso;

        if($request->personalEvaluado != 0 || $request->personalEvaluado != null){
            $model->porcientoAprobadoEvaluado = $request->aprobado * 100/$request->personalEvaluado;
            $model->porcientoSuspensoEvaluado = $request->suspenso * 100 / $request->personalEvaluado;
        }else {
            $model->porcientoAprobadoEvaluado = 0;
            $model->porcientoSuspensoEvaluado = 0;
        }

        $model->puntuacionObtenidaSegundaEval = $request->puntuacionObtenidaSegundaEval;
        $model->requisitoIncumplidoSegundaEval = $request->requisitoIncumplidoSegundaEval;
        $model->cantidadPuntosCriticoSegundaEval = $request->cantidadPuntosCriticoSegundaEval;
        $model->observacionSegundaEval = $request->observacionSegundaEval;

        $model->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $model->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        $model->otorgadoCertificado = $request->otorgadoCertificado;

        $model->mantenerCertificado = $request->mantenerCertificado;
        $model->suspenderTemporalCertificado = $request->suspenderTemporalCertificado;
        $model->cancelarCertificado = $request->cancelarCertificado;

        $model->renovarCertificado = $request->renovarCertificado;

        $model->elavoradoPor = $request->elavoradoPor;
        $model->nombreApellidos = $request->nombreApellidos;
        $model->fechaCierreDictamen = $request->fechaCierreDictamen;

        $model->aprobadoDelegadoTerritorialMintur = $request->aprobadoDelegadoTerritorialMintur;
        $model->nombreApellidosDelegadoTerritorio = $request->nombreApellidosDelegadoTerritorio;
        $model->fechaAprobacionDelegadoTerritorio = $request->fechaAprobacionDelegadoTerritorio;

        $model->aprobadoAutoridadadSanitariaTerritorio = $request->aprobadoAutoridadadSanitariaTerritorio;
        $model->nombreApellidosAutoridadadSanitariaTerritorio = $request->nombreApellidosAutoridadadSanitariaTerritorio;
        $model->fechaAprobacionAutoridadadSanitariaTerritorio = $request->fechaAprobacionAutoridadadSanitariaTerritorio;
        $model->mttipoeval_id = $request->mttipoeval_id;
        if ($model->save()){
            return new MTDictamenResource($model);
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
        $model = MTDictamenTMHS::findOrFail($id);
        if ($model->delete()){
            return new MTDictamenResource($model);
        }
    }
}
