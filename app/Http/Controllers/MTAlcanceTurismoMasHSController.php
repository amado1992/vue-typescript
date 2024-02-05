<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTAlcanceTurismoMasHSResource;
use App\Models\MTAlcanceTurismoMasHS;
use App\Models\MTInstalacion;
use App\Models\MTProcesoTurismoMasHigienicoSeguro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MTAlcanceTurismoMasHSController extends Controller
{
    public function all_table_alcance(){

        $all_table = DB::table('mtalcanceturismomashs')->max('id');
        return $all_table;
    }

    public function agrupacion($id){
        $lists = DB::table('mttipoinst')
            ->join('mtinstalacion', 'mttipoinst.id', '=', 'mtinstalacion.tipoInst_id')
            ->join('mttipoinstturismomashs', 'mttipoinst.id', '=', 'mttipoinstturismomashs.mttipoinst_id')
            ->join('mtvalores', 'mtvalores.id', '=', 'mttipoinstturismomashs.mtvalor_id')
            ->select('mtvalores.nombre')
            ->where('mtinstalacion.id', '=', $id)
            ->get();
        return $lists;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lists = MTAlcanceTurismoMasHS::with(['instalacion.municipio.provincia', 'instalacion.entidades.osde'])->paginate(100);

        return MTAlcanceTurismoMasHSResource::collection($lists);
    }


    public function  alcances($id){
        $lists = MTAlcanceTurismoMasHS::with(
            ['proceso_turismo_mas_higienico_seguro.instalacion.municipio.provincia',
             'instalacion.municipio.provincia', 'instalacion.entidades.osde'])->where('proceso_id', $id)->get();
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
        $anno = date('Y');
        $cont = 1;
        $last = MTAlcanceTurismoMasHS::all()->last();

        if ($last){
            $created = new Carbon($last->created_at);
            $created = $created->format('Y');

            if ($created < $anno){
                $cont = 1;
            }else {
                $cont = explode( '-', $last->numeroRegistro)[2] + 1;
            }
        }

        $proceso_principal = new MTProcesoTurismoMasHigienicoSeguroController();
        $provincia = $proceso_principal->get_provincia($request->instalacion_id);

        $instalacion = MTInstalacion::findOrFail($request->instalacion_id);

        $agrupacion = $this->agrupacion($request->instalacion_id);
        $request->agrupacion = $agrupacion[0]->nombre;

        //$max_id_alcance = $this->all_table_alcance();

        $time = Carbon::now()->format('Y');
        $code_provincia = $provincia[0]->nombre;

       /* if (is_null($max_id_alcance)){
            $max_id_alcance = 1;
        }*/

        //$request->numeroRegistro = $code_provincia."-".$instalacion->nombre."-".$max_id_alcance."-".$time;
        //$request->numeroRegistro = $code_provincia."-representante-".$max_id_alcance."-".$time;
        $request->numeroRegistro = $code_provincia."-representante-".$cont."-".$time;

        $validator = Validator::make($request->all(), [
            'autodiagnostico' => 'required',
            'capacitacionInicial' => 'required',
            'fechaEntregaMintur' => 'required',
            'instalacion_id' => 'required',
            'incluyeAlcance' => 'required',
            'proceso_id' => 'required'


        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }

        $model = new MTAlcanceTurismoMasHS();

        //REGISTRO
        $model->numeroRegistro = $request->numeroRegistro;
        $model->autodiagnostico = $request->autodiagnostico;
        $model->capacitacionInicial = $request->capacitacionInicial;
        $model->fechaEntregaMintur = $request->fechaEntregaMintur;
        $model->instalacion_id = $request->instalacion_id;
        $model->proceso_id = $request->proceso_id;

        $model->agrupacion = $request->agrupacion;
        $model->alcance = $request->alcance;
        $model->incluyeAlcance = $request->incluyeAlcance;

        //EVALUACION
        $model->fechaPrevistaEvaluacion = $request->fechaPrevistaEvaluacion;
        $model->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $model->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        //CERTIFICADO
        $model->fechaOtorgado = $request->fechaOtorgado;
        $model->fechaDenegado = $request->fechaDenegado;
        $model->requisitoIncumplido = $request->requisitoIncumplido;

        //RENOVACION
        $model->fechaRenovadoCertificado = $request->fechaRenovadoCertificado;
        $model->observacion = $request->observacion;


        if ($model->save()){
            $object = MTProcesoTurismoMasHigienicoSeguro::findOrFail($request->proceso_id);
            $object->alcance = $object->alcance + 1;
            $object->save();
            return new MTAlcanceTurismoMasHSResource($model);
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
        $model = MTAlcanceTurismoMasHS::findOrFail($id);
        return new MTAlcanceTurismoMasHSResource($model);
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
        $model = MTAlcanceTurismoMasHS::findOrFail($id);
        //REGISTRO
        $model->numeroRegistro = $request->numeroRegistro;
        $model->autodiagnostico = $request->autodiagnostico;
        $model->capacitacionInicial = $request->capacitacionInicial;
        $model->fechaEntregaMintur = $request->fechaEntregaMintur;
        $model->instalacion_id = $request->instalacion_id;
        $model->proceso_id = $request->proceso_id;

        $model->agrupacion = $request->agrupacion;
        $model->alcance = $request->alcance;
        $model->incluyeAlcance = $request->incluyeAlcance;

        //EVALUACION
        $model->fechaPrevistaEvaluacion = $request->fechaPrevistaEvaluacion;
        $model->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $model->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        //CERTIFICADO
        $model->fechaOtorgado = $request->fechaOtorgado;
        $model->fechaDenegado = $request->fechaDenegado;
        $model->requisitoIncumplido = $request->requisitoIncumplido;

        //RENOVACION
        $model->fechaRenovadoCertificado = $request->fechaRenovadoCertificado;
        $model->observacion = $request->observacion;


        if ($model->save()){
            return new MTAlcanceTurismoMasHSResource($model);
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
        $model = MTAlcanceTurismoMasHS::findOrFail($id);
        if ($model->delete()){
            $object = MTProcesoTurismoMasHigienicoSeguro::findOrFail($model->proceso_id);
            $object->alcance = $object->alcance - 1;
            $object->save();
            return new MTAlcanceTurismoMasHSResource($model);
        }
    }
}
