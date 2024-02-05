<?php

namespace App\Http\Controllers;

use App\Http\Resources\MTInstalacionCertificadaResource;
use App\Http\Resources\MTProcesoTurismoMasHigienicoSeguroResource;
use App\Models\MTInstalacion;
use App\Models\MTInstalacionCertificada;
use App\Models\MTProcesoTurismoMasHigienicoSeguro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use function Sodium\add;

class MTProcesoTurismoMasHigienicoSeguroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get_provincia($id){
        $provincia = DB::table('mtinstalacion')
            ->join('mtmunicipio', 'mtmunicipio.id', '=', 'mtinstalacion.municipio_id')
            ->join('mtprovincia', 'mtprovincia.id', '=', 'mtmunicipio.provincia_id')
            ->select('mtprovincia.nombre')
            ->where('mtinstalacion.id', '=', $id)
            ->get();
        return $provincia;
    }

    public function all_table(){

        $all_table = DB::table('mtprocesosturismomashigienicoseguro')->max('id');
        return $all_table;
    }

    public function index()
    {
        //$procesos_turismo_mas_higienico_seguro = MTProcesoTurismoMasHigienicoSeguro::with('instalacion')->paginate(100);
        /* $procesos_turismo_mas_higienico_seguro = MTProcesoTurismoMasHigienicoSeguro::all();
     $procesos_turismo_mas_higienico_seguro = $procesos_turismo_mas_higienico_seguro->load('instalacion.municipios.provincia');
*/
        $procesos_turismo_mas_higienico_seguro = MTProcesoTurismoMasHigienicoSeguro::with(['instalacion.municipio.provincia', 'instalacion.entidades.osde'])->paginate(100);

        return MTProcesoTurismoMasHigienicoSeguroResource::collection($procesos_turismo_mas_higienico_seguro);
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
        $last = MTProcesoTurismoMasHigienicoSeguro::all()->last();

        if ($last){
            $created = new Carbon($last->created_at);
            $created = $created->format('Y');

            if ($created < $anno){
                $cont = 1;
            }else {
                $cont = explode( '-', $last->numeroRegistro)[2] + 1;
            }
        }

        try {
            $alcance = new MTAlcanceTurismoMasHSController();
            $agrupacion = $alcance->agrupacion($request->instalacion_id);
            $request->agrupacion = $agrupacion[0]->nombre;
        }catch (\Throwable $e){
            /*report($e);//No genera un error para la pagina
            return false;*/
            abort(500,"Error en el campo agrupacion linia 71 ".$e->getMessage());

        }

        $provincia = $this->get_provincia($request->instalacion_id);
       //$instalacion = MTInstalacion::findOrFail($request->instalacion_id);

        //$max_id = $this->all_table();
        $time = Carbon::now()->format('Y');
        $code_provincia = $provincia[0]->nombre;

       /* if (is_null($max_id)){
            $max_id = 1;
        }*/
        //$request->numeroRegistro = $code_provincia."-".$instalacion->nombre."-".$max_id."-".$time;
       // $request->numeroRegistro = $code_provincia."-principal-".$max_id."-".$time;
        $request->numeroRegistro = $code_provincia."-principal-".$cont."-".$time;

        $validator = Validator::make($request->all(), [
            'autodiagnostico' => 'required',
            'capacitacionInicial' => 'required',
            'fechaEntregaMintur' => 'required',
            'instalacion_id' => 'required',
            'incluyeAlcance'  => 'required'


        ]);
        if ($validator->fails()){
            return response()->json(['status_code' => 400, 'message' => 'Bad request']);
        }

        $proceso_turismo_mas_higienico_seguro = new MTProcesoTurismoMasHigienicoSeguro();

        //REGISTRO
        $proceso_turismo_mas_higienico_seguro->numeroRegistro = $request->numeroRegistro;
        $proceso_turismo_mas_higienico_seguro->autodiagnostico = $request->autodiagnostico;
        $proceso_turismo_mas_higienico_seguro->capacitacionInicial = $request->capacitacionInicial;
        $proceso_turismo_mas_higienico_seguro->fechaEntregaMintur = $request->fechaEntregaMintur;
        $proceso_turismo_mas_higienico_seguro->instalacion_id = $request->instalacion_id;

        $proceso_turismo_mas_higienico_seguro->agrupacion = $request->agrupacion;
        $proceso_turismo_mas_higienico_seguro->alcance = $request->alcance;
        $proceso_turismo_mas_higienico_seguro->incluyeAlcance = $request->incluyeAlcance;

        //EVALUACION
        $proceso_turismo_mas_higienico_seguro->fechaPrevistaEvaluacion = $request->fechaPrevistaEvaluacion;
        $proceso_turismo_mas_higienico_seguro->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $proceso_turismo_mas_higienico_seguro->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        //CERTIFICADO
        $proceso_turismo_mas_higienico_seguro->fechaOtorgado = $request->fechaOtorgado;
        $proceso_turismo_mas_higienico_seguro->fechaDenegado = $request->fechaDenegado;
        $proceso_turismo_mas_higienico_seguro->requisitoIncumplido = $request->requisitoIncumplido;

        //RENOVACION
        $proceso_turismo_mas_higienico_seguro->fechaRenovadoCertificado = $request->fechaRenovadoCertificado;
        $proceso_turismo_mas_higienico_seguro->observacion = $request->observacion;


        if ($proceso_turismo_mas_higienico_seguro->save()){
            return new MTProcesoTurismoMasHigienicoSeguroResource($proceso_turismo_mas_higienico_seguro);
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
        $proceso_turismo_mas_higienico_seguro = MTProcesoTurismoMasHigienicoSeguro::findOrFail($id);
        return new MTProcesoTurismoMasHigienicoSeguroResource($proceso_turismo_mas_higienico_seguro);
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
        $proceso_turismo_mas_higienico_seguro = MTProcesoTurismoMasHigienicoSeguro::findOrFail($id);

        //REGISTRO
        $proceso_turismo_mas_higienico_seguro->numeroRegistro = $request->numeroRegistro;
        $proceso_turismo_mas_higienico_seguro->autodiagnostico = $request->autodiagnostico;
        $proceso_turismo_mas_higienico_seguro->capacitacionInicial = $request->capacitacionInicial;
        $proceso_turismo_mas_higienico_seguro->fechaEntregaMintur = $request->fechaEntregaMintur;
        $proceso_turismo_mas_higienico_seguro->instalacion_id = $request->instalacion_id;

        $proceso_turismo_mas_higienico_seguro->agrupacion = $request->agrupacion;
        $proceso_turismo_mas_higienico_seguro->alcance = $request->alcance;
        $proceso_turismo_mas_higienico_seguro->incluyeAlcance = $request->incluyeAlcance;

        //EVALUACION
        $proceso_turismo_mas_higienico_seguro->fechaPrevistaEvaluacion = $request->fechaPrevistaEvaluacion;
        $proceso_turismo_mas_higienico_seguro->fechaPrimeraEvaluacion = $request->fechaPrimeraEvaluacion;
        $proceso_turismo_mas_higienico_seguro->fechaSegundaEvaluacion = $request->fechaSegundaEvaluacion;

        //CERTIFICADO
        $proceso_turismo_mas_higienico_seguro->fechaOtorgado = $request->fechaOtorgado;
        $proceso_turismo_mas_higienico_seguro->fechaDenegado = $request->fechaDenegado;
        $proceso_turismo_mas_higienico_seguro->requisitoIncumplido = $request->requisitoIncumplido;


        //$proceso_turismo_mas_higienico_seguro->fechaSuspensionTemporalCertificado = $request->fechaSuspensionTemporalCertificado;
        //$proceso_turismo_mas_higienico_seguro->fechaRetiroSuspensionTemporalCertificado = $request->fechaRetiroSuspensionTemporalCertificado;
        //$proceso_turismo_mas_higienico_seguro->fechaCanceladoCertificado = $request->fechaCanceladoCertificado;
        //$proceso_turismo_mas_higienico_seguro->requisitoIncumplido = $request->requisitoIncumplido;

        //RENOVACION
        $proceso_turismo_mas_higienico_seguro->fechaRenovadoCertificado = $request->fechaRenovadoCertificado;
        $proceso_turismo_mas_higienico_seguro->observacion = $request->observacion;


        if ($proceso_turismo_mas_higienico_seguro->save()){
            return new MTProcesoTurismoMasHigienicoSeguroResource($proceso_turismo_mas_higienico_seguro);
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
        $proceso_turismo_mas_higienico_seguro = MTProcesoTurismoMasHigienicoSeguro::findOrFail($id);
        if ($proceso_turismo_mas_higienico_seguro->delete()){
        return new MTProcesoTurismoMasHigienicoSeguroResource($proceso_turismo_mas_higienico_seguro);
        }

    }
}
