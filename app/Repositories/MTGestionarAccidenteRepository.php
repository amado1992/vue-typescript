<?php


namespace App\Repositories;


use App\Models\MTGestionarAccidente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTGestionarAccidenteRepository
{
  private $mtGestionarAccidente;

  public function __construct(MTGestionarAccidente $mtGestionarAccidente)
  {
    $this->mtGestionarAccidente = $mtGestionarAccidente;
  }

  protected $fieldSearchable = [
    'radicacion', 'fecha', 'lugar', 'hora', 'imputable', 'clasificacion_accidente_id',
    'victima_accidentes_id', 'fallecido', 'herido', 'perdidas_materiales', 'vehiculo_empresa_id',
    'vehiculo_ajeno_id', 'causa_accidente_id', 'annos_experiencia', 'nombre_apellidos',
    'expediente_laboral', 'estacion_pnr', 'tribunal_competente', 'atestado'
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

  public function model()
  {
    return MTGestionarAccidente::class;
  }

  public function listMTGestionarAccidente()
  {
    $mtGestionarAccidente = $this->mtGestionarAccidente
      ->with(['instalaciones:id,nombre','clasificacionaccidentes:id,clasificacion_accidente',
        'victimaaccidentes:id,victima_accidente', 'vehiculoempresas:id,marca,matricula',
        'vehiculoajenos:id,tipo,marca,matricula', 'causaaccidentes:id,causa_accidente'])
      ->orderBy('created_at', 'desc')->get();

    if (!isset($mtGestionarAccidente) || $mtGestionarAccidente == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay accidentes que mostrar']);
    return response()->json($mtGestionarAccidente);
  }

  public function Noradicacion()
  {
    $anno = date('Y');
    $arreglo = DB::table('mtgestionaraccidentes')->whereYear('mtgestionaraccidentes.created_at', '=', $anno)->count();
    $last_radicacion = MTGestionarAccidente::all()->last();
    if (empty($last_radicacion))
      $cont = 1;
    else {
      if ($last_radicacion->created_at->format('Y') < $anno)
        $cont = 1;
      else
        $cont = $arreglo + 1;
    }
    return $anno . '/' . $cont;
  }

  public function createMTGestionarAccidente($request, $instalacionId)
  {
    $data = new MTGestionarAccidente([
      'instalacion_id' => $instalacionId,
      'radicacion' => $this->Noradicacion(), //Valor consecutivo: año/número (Ejemplo: 2021/10.). Cada año se reinicia el contador.
      'fecha' => $request['fecha'],
      'lugar' => $request['lugar'],
      'hora' => $request['hora'],
      'imputable' => $request['imputable'],
      'accidente_via' => $request['accidente_via'],
      'accidente_base' => $request['accidente_base'],
      'clasificacion_accidente_id' => $request['clasificacion_accidente_id'],
      'victima_accidentes_id' => $request['victima_accidentes_id'],
      'fallecido' => $request['fallecido'],
      'herido' => $request['herido'],
      'perdidas_materiales' => $request['perdidas_materiales'],
      'vehiculo_empresa_id' => $request['vehiculo_empresa_id'],
      'vehiculo_ajeno_id' => $request['vehiculo_ajeno_id'],
      'causa_accidente_id' => $request['causa_accidente_id'],
      'annos_experiencia' => $request['annos_experiencia'],
      'nombre_apellidos' => $request['nombre_apellidos'],
      'expediente_laboral' => $request['expediente_laboral'],
      'estacion_pnr' => $request['estacion_pnr'],
      'tribunal_competente' => $request['tribunal_competente'],
      'atestado' => $request['atestado']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTGestionarAccidente($id, Request $request)
  {
    $data = MTGestionarAccidente::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  public function eliminarMTGestionarAccidente($id)
  {
    MTGestionarAccidente::destroy($id);
    return response()->json('Gestión de accidente eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

  // Warning: Division by zero.
  public function warningdivisionbyzero($var1, $var2){
    if($var1 != 0 && $var2 != 0)
      return $var1 / $var2;
    else
      return 0;
  }

// Reporte -> Mostrar por cada indicador la información estadística de accidentalidad
  public function reporte_accidentalidad($request)
  {
    $ano_anterior = date('Y') - 1;
    $ano_actual = date('Y');

    //<editor-fold defaultstate="collapsed" desc="Mes Año Anterior">
    $mes_ano_anterior_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])
      ->where('imputable', '1')->count();

    $mes_ano_anterior_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('imputable', '0')->count();

    $mes_ano_anterior_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $mes_ano_anterior_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $mes_ano_anterior_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $mes_ano_anterior_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('herido');

    $mes_ano_anterior_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('fallecido');

    $mes_ano_anterior_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('perdidas_materiales');

    $mes_ano_anterior_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '1')->count();

    $mes_ano_anterior_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $mes_ano_anterior_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $mes_ano_anterior_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $mes_ano_anterior_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $mes_ano_anterior_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $mes_ano_anterior_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $mes_ano_anterior_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $mes_ano_anterior_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_via', '1')->count();

    $mes_ano_anterior_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_base', '1')->count();

    $mes_ano_anterior_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_anterior)
      ->where('mes', '=', $request['mes'])->sum('km_recorridos');

    $mes_ano_anterior_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Mes Año Actual">
    $mes_ano_actual_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('imputable', '1')->count();

    $mes_ano_actual_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('imputable', '=','0')->count();

    $mes_ano_actual_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $mes_ano_actual_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $mes_ano_actual_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $mes_ano_actual_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('herido');

    $mes_ano_actual_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('fallecido');

    $mes_ano_actual_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('perdidas_materiales');

    $mes_ano_actual_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '1')->count();


    $mes_ano_actual_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $mes_ano_actual_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $mes_ano_actual_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $mes_ano_actual_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $mes_ano_actual_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $mes_ano_actual_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $mes_ano_actual_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $mes_ano_actual_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_via', '1')->count();

    $mes_ano_actual_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_base', '1')->count();

    $mes_ano_actual_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_actual)
      ->where('mes', '=', $request['mes'])->sum('km_recorridos');

    $mes_ano_actual_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Acumulado Año Anterior">
    $acumulado_ano_anterior_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '1')->count();

    $acumulado_ano_anterior_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '0')->count();

    $acumulado_ano_anterior_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $acumulado_ano_anterior_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $acumulado_ano_anterior_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $acumulado_ano_anterior_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('herido');

    $acumulado_ano_anterior_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('fallecido');

    $acumulado_ano_anterior_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('perdidas_materiales');

    $acumulado_ano_anterior_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '1')->count();

    $acumulado_ano_anterior_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $acumulado_ano_anterior_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $acumulado_ano_anterior_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $acumulado_ano_anterior_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $acumulado_ano_anterior_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $acumulado_ano_anterior_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $acumulado_ano_anterior_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $acumulado_ano_anterior_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_via', '1')->count();

    $acumulado_ano_anterior_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_base', '1')->count();

    $acumulado_ano_anterior_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_anterior)
      ->where('mes', '<=', $request['mes'])->sum('km_recorridos');

    $acumulado_ano_anterior_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Acumulado Año Actual">
    $acumulado_ano_actual_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '1')->count();

    $acumulado_ano_actual_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '0')->count();

    $acumulado_ano_actual_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $acumulado_ano_actual_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $acumulado_ano_actual_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $acumulado_ano_actual_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('herido');

    $acumulado_ano_actual_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('fallecido');

    $acumulado_ano_actual_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('perdidas_materiales');

    $acumulado_ano_actual_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '1')->count();

    $acumulado_ano_actual_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $acumulado_ano_actual_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $acumulado_ano_actual_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $acumulado_ano_actual_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $acumulado_ano_actual_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $acumulado_ano_actual_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $acumulado_ano_actual_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $acumulado_ano_actual_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_via', '1')->count();

    $acumulado_ano_actual_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_base', '1')->count();

    $acumulado_ano_actual_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_actual)
      ->where('mes', '<=', $request['mes'])->sum('km_recorridos');

    $acumulado_ano_actual_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Total Año Anterior">
    $total_ano_anterior_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('imputable', '1')->count();

    $total_ano_anterior_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('imputable', '0')->count();

    $total_ano_anterior_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('clasificacion_accidente_id', '1')->count();

    $total_ano_anterior_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('clasificacion_accidente_id', '2')->count();

    $total_ano_anterior_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('clasificacion_accidente_id', '3')->count();

    $total_ano_anterior_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->sum('herido');

    $total_ano_anterior_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->sum('fallecido');

    $total_ano_anterior_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->sum('perdidas_materiales');

    $total_ano_anterior_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('victima_accidentes_id', '1')->count();

    $total_ano_anterior_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('victima_accidentes_id', '2')->count();

    $total_ano_anterior_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('victima_accidentes_id', '3')->count();

    $total_ano_anterior_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '1')->count();

    $total_ano_anterior_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '2')->count();

    $total_ano_anterior_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '3')->count();

    $total_ano_anterior_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '4')->count();

    $total_ano_anterior_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '5')->count();

    $total_ano_anterior_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('accidente_via', '1')->count();

    $total_ano_anterior_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('accidente_base', '1')->count();

    $total_ano_anterior_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_anterior)->sum('km_recorridos');

    $total_ano_anterior_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Total Año Actual">
    $total_ano_actual_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('imputable', '1')->count();

    $total_ano_actual_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('imputable', '0')->count();

    $total_ano_actual_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('clasificacion_accidente_id', '1')->count();

    $total_ano_actual_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('clasificacion_accidente_id', '2')->count();

    $total_ano_actual_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('clasificacion_accidente_id', '3')->count();

    $total_ano_actual_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->sum('herido');

    $total_ano_actual_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->sum('fallecido');

    $total_ano_actual_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->sum('perdidas_materiales');

    $total_ano_actual_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('victima_accidentes_id', '1')->count();

    $total_ano_actual_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('victima_accidentes_id', '2')->count();

    $total_ano_actual_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('victima_accidentes_id', '3')->count();

    $total_ano_actual_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '1')->count();

    $total_ano_actual_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '2')->count();

    $total_ano_actual_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '3')->count();

    $total_ano_actual_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '4')->count();

    $total_ano_actual_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '5')->count();

    $total_ano_actual_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('accidente_via', '1')->count();

    $total_ano_actual_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('accidente_base', '1')->count();

    $total_ano_actual_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_actual)->sum('km_recorridos');

    $total_ano_actual_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->count();
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Filtros">
    if (!empty($request['instalacion_id'])) {
      $mes_ano_anterior_imputable = $mes_ano_anterior_imputable->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_imputable = $mes_ano_actual_imputable->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_imputable = $acumulado_ano_anterior_imputable->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_imputable = $acumulado_ano_actual_imputable->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_imputable = $total_ano_anterior_imputable->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_imputable = $total_ano_actual_imputable->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_no_imputable = $mes_ano_anterior_no_imputable->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_no_imputable = $mes_ano_actual_no_imputable->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_no_imputable = $acumulado_ano_anterior_no_imputable->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_no_imputable = $acumulado_ano_actual_no_imputable->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_no_imputable = $total_ano_anterior_no_imputable->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_no_imputable = $total_ano_actual_no_imputable->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_leves = $mes_ano_anterior_leves->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_leves = $mes_ano_actual_leves->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_leves = $acumulado_ano_anterior_leves->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_leves = $acumulado_ano_actual_leves->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_leves = $total_ano_anterior_leves->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_leves = $total_ano_actual_leves->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_graves = $mes_ano_anterior_graves->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_graves = $mes_ano_actual_graves->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_graves = $acumulado_ano_anterior_graves->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_graves = $acumulado_ano_actual_graves->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_graves = $total_ano_anterior_graves->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_graves = $total_ano_actual_graves->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_catastroficos = $mes_ano_anterior_catastroficos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_catastroficos = $mes_ano_actual_catastroficos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_catastroficos = $acumulado_ano_anterior_catastroficos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_catastroficos = $acumulado_ano_actual_catastroficos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_catastroficos = $total_ano_anterior_catastroficos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_catastroficos = $total_ano_actual_catastroficos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_heridos = $mes_ano_anterior_heridos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_heridos = $mes_ano_actual_heridos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_heridos = $acumulado_ano_anterior_heridos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_heridos = $acumulado_ano_actual_heridos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_heridos = $total_ano_anterior_heridos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_heridos = $total_ano_actual_heridos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_fallecidos = $mes_ano_anterior_fallecidos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_fallecidos = $mes_ano_actual_fallecidos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_fallecidos = $acumulado_ano_anterior_fallecidos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_fallecidos = $acumulado_ano_actual_fallecidos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_fallecidos = $total_ano_anterior_fallecidos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_fallecidos = $total_ano_actual_fallecidos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_perdidas_materiales = $mes_ano_anterior_perdidas_materiales->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_perdidas_materiales = $mes_ano_actual_perdidas_materiales->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_perdidas_materiales = $acumulado_ano_anterior_perdidas_materiales->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_perdidas_materiales = $acumulado_ano_actual_perdidas_materiales->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_perdidas_materiales = $total_ano_anterior_perdidas_materiales->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_perdidas_materiales = $total_ano_actual_perdidas_materiales->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_peatones = $mes_ano_anterior_peatones->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_peatones = $mes_ano_actual_peatones->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_peatones = $acumulado_ano_anterior_peatones->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_peatones = $acumulado_ano_actual_peatones->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_peatones = $total_ano_anterior_peatones->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_peatones = $total_ano_actual_peatones->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_ciclos = $mes_ano_anterior_ciclos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_ciclos = $mes_ano_actual_ciclos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_ciclos = $acumulado_ano_anterior_ciclos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_ciclos = $acumulado_ano_actual_ciclos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_ciclos = $total_ano_anterior_ciclos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_ciclos = $total_ano_actual_ciclos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_animales = $mes_ano_anterior_animales->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_animales = $mes_ano_actual_animales->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_animales = $acumulado_ano_anterior_animales->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_animales = $acumulado_ano_actual_animales->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_animales = $total_ano_anterior_animales->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_animales = $total_ano_actual_animales->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_exceso_velocidad = $mes_ano_anterior_exceso_velocidad->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_exceso_velocidad = $mes_ano_actual_exceso_velocidad->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_exceso_velocidad = $acumulado_ano_anterior_exceso_velocidad->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_exceso_velocidad = $acumulado_ano_actual_exceso_velocidad->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_exceso_velocidad = $total_ano_anterior_exceso_velocidad->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_exceso_velocidad = $total_ano_actual_exceso_velocidad->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_no_atencion = $mes_ano_anterior_no_atencion->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_no_atencion = $mes_ano_actual_no_atencion->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_no_atencion = $acumulado_ano_anterior_no_atencion->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_no_atencion = $acumulado_ano_actual_no_atencion->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_no_atencion = $total_ano_anterior_no_atencion->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_no_atencion = $total_ano_actual_no_atencion->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_desperfectos_tecnicos = $mes_ano_anterior_desperfectos_tecnicos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_desperfectos_tecnicos = $mes_ano_actual_desperfectos_tecnicos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_desperfectos_tecnicos = $acumulado_ano_anterior_desperfectos_tecnicos->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_desperfectos_tecnicos = $acumulado_ano_actual_desperfectos_tecnicos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_desperfectos_tecnicos = $total_ano_anterior_desperfectos_tecnicos->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_desperfectos_tecnicos = $total_ano_actual_desperfectos_tecnicos->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_sustancias = $mes_ano_anterior_sustancias->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_sustancias = $mes_ano_actual_sustancias->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_sustancias = $acumulado_ano_anterior_sustancias->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_sustancias = $acumulado_ano_actual_sustancias->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_sustancias = $total_ano_anterior_sustancias->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_sustancias = $total_ano_actual_sustancias->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_otros = $mes_ano_anterior_otros->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_otros = $mes_ano_actual_otros->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_otros = $acumulado_ano_anterior_otros->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_otros = $acumulado_ano_actual_otros->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_otros = $total_ano_anterior_otros->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_otros = $total_ano_actual_otros->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_via = $mes_ano_anterior_via->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_via = $mes_ano_actual_via->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_via = $acumulado_ano_anterior_via->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_via = $acumulado_ano_actual_via->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_via = $total_ano_anterior_via->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_via = $total_ano_actual_via->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_base = $mes_ano_anterior_base->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_base = $mes_ano_actual_base->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_base = $acumulado_ano_anterior_base->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_base = $acumulado_ano_actual_base->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_base = $total_ano_anterior_base->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_base = $total_ano_actual_base->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_anterior_kilometros = $mes_ano_anterior_kilometros->where('instalacion_id', $request['instalacion_id']);
      $mes_ano_actual_kilometros = $mes_ano_actual_kilometros->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_anterior_kilometros = $acumulado_ano_anterior_kilometros->where('instalacion_id', $request['instalacion_id']);
      $acumulado_ano_actual_kilometros = $acumulado_ano_actual_kilometros->where('instalacion_id', $request['instalacion_id']);
      $total_ano_anterior_kilometros = $total_ano_anterior_kilometros->where('instalacion_id', $request['instalacion_id']);
      $total_ano_actual_kilometros = $total_ano_actual_kilometros->where('instalacion_id', $request['instalacion_id']);
    }

    if (!empty($request['osde'])) {
      $mes_ano_anterior_imputable = $mes_ano_anterior_imputable->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_imputable = $mes_ano_actual_imputable->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_imputable = $acumulado_ano_anterior_imputable->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_imputable = $acumulado_ano_actual_imputable->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_imputable = $total_ano_anterior_imputable->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_imputable = $total_ano_actual_imputable->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_no_imputable = $mes_ano_anterior_no_imputable->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_no_imputable = $mes_ano_actual_no_imputable->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_no_imputable = $acumulado_ano_anterior_no_imputable->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_no_imputable = $acumulado_ano_actual_no_imputable->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_no_imputable = $total_ano_anterior_no_imputable->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_no_imputable = $total_ano_actual_no_imputable->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_leves = $mes_ano_anterior_leves->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_leves = $mes_ano_actual_leves->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_leves = $acumulado_ano_anterior_leves->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_leves = $acumulado_ano_actual_leves->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_leves = $total_ano_anterior_leves->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_leves = $total_ano_actual_leves->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_graves = $mes_ano_anterior_graves->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_graves = $mes_ano_actual_graves->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_graves = $acumulado_ano_anterior_graves->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_graves = $acumulado_ano_actual_graves->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_graves = $total_ano_anterior_graves->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_graves = $total_ano_actual_graves->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_catastroficos = $mes_ano_anterior_catastroficos->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_catastroficos = $mes_ano_actual_catastroficos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_catastroficos = $acumulado_ano_anterior_catastroficos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_catastroficos = $acumulado_ano_actual_catastroficos->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_catastroficos = $total_ano_actual_catastroficos->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_heridos = $mes_ano_anterior_heridos->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_heridos = $mes_ano_actual_heridos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_heridos = $acumulado_ano_anterior_heridos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_heridos = $acumulado_ano_actual_heridos->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_heridos = $total_ano_anterior_heridos->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_heridos = $total_ano_actual_heridos->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_fallecidos = $mes_ano_anterior_fallecidos->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_fallecidos = $mes_ano_actual_fallecidos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_fallecidos = $acumulado_ano_actual_fallecidos->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_fallecidos = $total_ano_anterior_fallecidos->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_fallecidos = $total_ano_actual_fallecidos->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_perdidas_materiales = $mes_ano_anterior_perdidas_materiales->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_perdidas_materiales = $mes_ano_actual_perdidas_materiales->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_perdidas_materiales = $acumulado_ano_anterior_perdidas_materiales->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_perdidas_materiales = $acumulado_ano_actual_perdidas_materiales->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_perdidas_materiales = $total_ano_anterior_perdidas_materiales->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_perdidas_materiales = $total_ano_actual_perdidas_materiales->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_peatones = $mes_ano_anterior_peatones->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_peatones = $mes_ano_actual_peatones->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_peatones = $acumulado_ano_anterior_peatones->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_peatones = $acumulado_ano_actual_peatones->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_peatones = $total_ano_actual_peatones->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_ciclos = $mes_ano_anterior_ciclos->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_ciclos = $mes_ano_actual_ciclos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_ciclos = $acumulado_ano_anterior_ciclos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_ciclos = $acumulado_ano_actual_ciclos->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_ciclos = $total_ano_anterior_ciclos->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_ciclos = $total_ano_actual_ciclos->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_animales = $mes_ano_anterior_animales->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_animales = $mes_ano_actual_animales->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_animales = $acumulado_ano_actual_animales->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_animales = $total_ano_anterior_animales->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_animales = $total_ano_actual_animales->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_exceso_velocidad = $mes_ano_actual_exceso_velocidad->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_exceso_velocidad = $acumulado_ano_anterior_exceso_velocidad->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_exceso_velocidad = $total_ano_anterior_exceso_velocidad->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_exceso_velocidad = $total_ano_actual_exceso_velocidad->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_no_atencion = $mes_ano_actual_no_atencion->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_no_atencion = $acumulado_ano_anterior_no_atencion->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_no_atencion = $acumulado_ano_actual_no_atencion->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_no_atencion = $total_ano_anterior_no_atencion->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_no_atencion = $total_ano_actual_no_atencion->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_desperfectos_tecnicos = $mes_ano_anterior_desperfectos_tecnicos->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_desperfectos_tecnicos = $mes_ano_actual_desperfectos_tecnicos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_desperfectos_tecnicos = $acumulado_ano_anterior_desperfectos_tecnicos->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_desperfectos_tecnicos = $acumulado_ano_actual_desperfectos_tecnicos->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_desperfectos_tecnicos = $total_ano_anterior_desperfectos_tecnicos->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_desperfectos_tecnicos = $total_ano_actual_desperfectos_tecnicos->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_sustancias = $mes_ano_anterior_sustancias->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_sustancias = $mes_ano_actual_sustancias->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_sustancias = $acumulado_ano_anterior_sustancias->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_sustancias = $acumulado_ano_actual_sustancias->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_sustancias = $total_ano_anterior_sustancias->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_sustancias = $total_ano_actual_sustancias->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_otros = $mes_ano_anterior_otros->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_otros = $mes_ano_actual_otros->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_otros = $acumulado_ano_anterior_otros->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_otros = $acumulado_ano_actual_otros->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_otros = $total_ano_anterior_otros->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_otros = $total_ano_actual_otros->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_via = $mes_ano_anterior_via->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_via = $mes_ano_actual_via->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_via = $acumulado_ano_anterior_via->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_via = $acumulado_ano_actual_via->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_via = $total_ano_anterior_via->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_via = $total_ano_actual_via->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_base = $mes_ano_anterior_base->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_base = $mes_ano_actual_base->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_base = $acumulado_ano_anterior_base->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_base = $acumulado_ano_actual_base->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_base = $total_ano_anterior_base->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_base = $total_ano_actual_base->where('mtosde.nombre', $request['osde']);
      $mes_ano_anterior_kilometros = $mes_ano_anterior_kilometros->where('mtosde.nombre', $request['osde']);
      $mes_ano_actual_kilometros = $mes_ano_actual_kilometros->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_anterior_kilometros = $acumulado_ano_anterior_kilometros->where('mtosde.nombre', $request['osde']);
      $acumulado_ano_actual_kilometros = $acumulado_ano_actual_kilometros->where('mtosde.nombre', $request['osde']);
      $total_ano_anterior_kilometros = $total_ano_anterior_kilometros->where('mtosde.nombre', $request['osde']);
      $total_ano_actual_kilometros = $total_ano_actual_kilometros->where('mtosde.nombre', $request['osde']);
    }

    if (!empty($request['provincia'])) {
      $mes_ano_anterior_imputable = $mes_ano_anterior_imputable->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_imputable = $mes_ano_actual_imputable->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_imputable = $acumulado_ano_anterior_imputable->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_imputable = $acumulado_ano_actual_imputable->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_imputable = $total_ano_anterior_imputable->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_no_imputable = $mes_ano_anterior_no_imputable->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_no_imputable = $mes_ano_actual_no_imputable->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_no_imputable = $acumulado_ano_anterior_no_imputable->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_no_imputable = $total_ano_anterior_no_imputable->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_no_imputable = $total_ano_actual_no_imputable->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_leves = $mes_ano_anterior_leves->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_leves = $mes_ano_actual_leves->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_leves = $acumulado_ano_anterior_leves->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_leves = $acumulado_ano_actual_leves->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_leves = $total_ano_anterior_leves->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_leves = $total_ano_actual_leves->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_graves = $mes_ano_actual_graves->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_graves = $acumulado_ano_actual_graves->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_graves = $total_ano_anterior_graves->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_graves = $total_ano_actual_graves->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_catastroficos = $mes_ano_anterior_catastroficos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_catastroficos = $mes_ano_actual_catastroficos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_catastroficos = $acumulado_ano_anterior_catastroficos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_catastroficos = $acumulado_ano_actual_catastroficos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_catastroficos = $total_ano_anterior_catastroficos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_catastroficos = $total_ano_actual_catastroficos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_heridos = $mes_ano_anterior_heridos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_heridos = $mes_ano_actual_heridos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_heridos = $acumulado_ano_anterior_heridos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_heridos = $acumulado_ano_actual_heridos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_heridos = $total_ano_anterior_heridos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_heridos = $total_ano_actual_heridos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_fallecidos = $mes_ano_anterior_fallecidos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_fallecidos = $mes_ano_actual_fallecidos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_fallecidos = $acumulado_ano_anterior_fallecidos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_fallecidos = $acumulado_ano_actual_fallecidos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_fallecidos = $total_ano_anterior_fallecidos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_fallecidos = $total_ano_actual_fallecidos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_perdidas_materiales = $mes_ano_anterior_perdidas_materiales->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_perdidas_materiales = $mes_ano_actual_perdidas_materiales->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_perdidas_materiales = $acumulado_ano_anterior_perdidas_materiales->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_perdidas_materiales = $acumulado_ano_actual_perdidas_materiales->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_perdidas_materiales = $total_ano_anterior_perdidas_materiales->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_perdidas_materiales = $total_ano_actual_perdidas_materiales->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_peatones = $mes_ano_anterior_peatones->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_peatones = $mes_ano_actual_peatones->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_peatones = $acumulado_ano_anterior_peatones->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_peatones = $acumulado_ano_actual_peatones->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_peatones = $total_ano_actual_peatones->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_ciclos = $mes_ano_anterior_ciclos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_ciclos = $mes_ano_actual_ciclos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_ciclos = $acumulado_ano_anterior_ciclos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_ciclos = $acumulado_ano_actual_ciclos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_ciclos = $total_ano_anterior_ciclos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_ciclos = $total_ano_actual_ciclos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_animales = $mes_ano_anterior_animales->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_animales = $mes_ano_actual_animales->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_animales = $acumulado_ano_anterior_animales->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_animales = $acumulado_ano_actual_animales->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_animales = $total_ano_anterior_animales->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_animales = $total_ano_actual_animales->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_exceso_velocidad = $mes_ano_anterior_exceso_velocidad->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_exceso_velocidad = $mes_ano_actual_exceso_velocidad->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_exceso_velocidad = $acumulado_ano_anterior_exceso_velocidad->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_exceso_velocidad = $acumulado_ano_actual_exceso_velocidad->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_exceso_velocidad = $total_ano_anterior_exceso_velocidad->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_exceso_velocidad = $total_ano_actual_exceso_velocidad->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_no_atencion = $mes_ano_actual_no_atencion->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_no_atencion = $acumulado_ano_anterior_no_atencion->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_no_atencion = $acumulado_ano_actual_no_atencion->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_no_atencion = $total_ano_anterior_no_atencion->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_no_atencion = $total_ano_actual_no_atencion->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_desperfectos_tecnicos = $mes_ano_anterior_desperfectos_tecnicos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_desperfectos_tecnicos = $mes_ano_actual_desperfectos_tecnicos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_desperfectos_tecnicos = $acumulado_ano_anterior_desperfectos_tecnicos->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_desperfectos_tecnicos = $acumulado_ano_actual_desperfectos_tecnicos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_desperfectos_tecnicos = $total_ano_anterior_desperfectos_tecnicos->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_desperfectos_tecnicos = $total_ano_actual_desperfectos_tecnicos->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_sustancias = $mes_ano_anterior_sustancias->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_sustancias = $mes_ano_actual_sustancias->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_sustancias = $acumulado_ano_anterior_sustancias->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_sustancias = $acumulado_ano_actual_sustancias->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_sustancias = $total_ano_anterior_sustancias->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_sustancias = $total_ano_actual_sustancias->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_otros = $mes_ano_anterior_otros->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_otros = $mes_ano_actual_otros->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_otros = $acumulado_ano_anterior_otros->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_otros = $acumulado_ano_actual_otros->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_otros = $total_ano_anterior_otros->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_otros = $total_ano_actual_otros->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_via = $mes_ano_anterior_via->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_via = $mes_ano_actual_via->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_via = $acumulado_ano_anterior_via->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_via = $acumulado_ano_actual_via->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_via = $total_ano_anterior_via->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_via = $total_ano_actual_via->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_base = $mes_ano_anterior_base->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_base = $mes_ano_actual_base->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_base = $acumulado_ano_anterior_base->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_base = $acumulado_ano_actual_base->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_base = $total_ano_anterior_base->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_base = $total_ano_actual_base->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_anterior_kilometros = $mes_ano_anterior_kilometros->where('mtprovincia.nombre', $request['provincia']);
      $mes_ano_actual_kilometros = $mes_ano_actual_kilometros->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_anterior_kilometros = $acumulado_ano_anterior_kilometros->where('mtprovincia.nombre', $request['provincia']);
      $acumulado_ano_actual_kilometros = $acumulado_ano_actual_kilometros->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_anterior_kilometros = $total_ano_anterior_kilometros->where('mtprovincia.nombre', $request['provincia']);
      $total_ano_actual_kilometros = $total_ano_actual_kilometros->where('mtprovincia.nombre', $request['provincia']);
    }

    if (!empty($request['delegacion'])) {
      $mes_ano_anterior_imputable = $mes_ano_anterior_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_imputable = $mes_ano_actual_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_imputable = $acumulado_ano_anterior_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_imputable = $acumulado_ano_actual_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_imputable = $total_ano_anterior_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_no_imputable = $mes_ano_anterior_no_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_no_imputable = $mes_ano_actual_no_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_no_imputable = $acumulado_ano_anterior_no_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_no_imputable = $total_ano_anterior_no_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_no_imputable = $total_ano_actual_no_imputable->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_leves = $mes_ano_anterior_leves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_leves = $mes_ano_actual_leves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_leves = $acumulado_ano_anterior_leves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_leves = $acumulado_ano_actual_leves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_leves = $total_ano_anterior_leves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_leves = $total_ano_actual_leves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_graves = $mes_ano_actual_graves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_graves = $acumulado_ano_actual_graves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_graves = $total_ano_anterior_graves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_graves = $total_ano_actual_graves->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_catastroficos = $mes_ano_anterior_catastroficos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_catastroficos = $mes_ano_actual_catastroficos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_catastroficos = $acumulado_ano_anterior_catastroficos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_catastroficos = $acumulado_ano_actual_catastroficos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_catastroficos = $total_ano_anterior_catastroficos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_catastroficos = $total_ano_actual_catastroficos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_heridos = $mes_ano_anterior_heridos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_heridos = $mes_ano_actual_heridos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_heridos = $acumulado_ano_anterior_heridos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_heridos = $acumulado_ano_actual_heridos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_heridos = $total_ano_anterior_heridos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_heridos = $total_ano_actual_heridos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_fallecidos = $mes_ano_anterior_fallecidos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_fallecidos = $mes_ano_actual_fallecidos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_fallecidos = $acumulado_ano_anterior_fallecidos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_fallecidos = $acumulado_ano_actual_fallecidos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_fallecidos = $total_ano_anterior_fallecidos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_fallecidos = $total_ano_actual_fallecidos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_perdidas_materiales = $mes_ano_anterior_perdidas_materiales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_perdidas_materiales = $mes_ano_actual_perdidas_materiales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_perdidas_materiales = $acumulado_ano_anterior_perdidas_materiales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_perdidas_materiales = $acumulado_ano_actual_perdidas_materiales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_perdidas_materiales = $total_ano_anterior_perdidas_materiales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_perdidas_materiales = $total_ano_actual_perdidas_materiales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_peatones = $mes_ano_anterior_peatones->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_peatones = $mes_ano_actual_peatones->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_peatones = $acumulado_ano_anterior_peatones->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_peatones = $acumulado_ano_actual_peatones->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_peatones = $total_ano_actual_peatones->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_ciclos = $mes_ano_anterior_ciclos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_ciclos = $mes_ano_actual_ciclos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_ciclos = $acumulado_ano_anterior_ciclos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_ciclos = $acumulado_ano_actual_ciclos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_ciclos = $total_ano_anterior_ciclos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_ciclos = $total_ano_actual_ciclos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_animales = $mes_ano_anterior_animales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_animales = $mes_ano_actual_animales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_animales = $acumulado_ano_anterior_animales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_animales = $acumulado_ano_actual_animales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_animales = $total_ano_anterior_animales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_animales = $total_ano_actual_animales->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_exceso_velocidad = $mes_ano_anterior_exceso_velocidad->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_exceso_velocidad = $mes_ano_actual_exceso_velocidad->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_exceso_velocidad = $acumulado_ano_anterior_exceso_velocidad->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_exceso_velocidad = $acumulado_ano_actual_exceso_velocidad->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_exceso_velocidad = $total_ano_anterior_exceso_velocidad->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_exceso_velocidad = $total_ano_actual_exceso_velocidad->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_no_atencion = $mes_ano_actual_no_atencion->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_no_atencion = $acumulado_ano_anterior_no_atencion->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_no_atencion = $acumulado_ano_actual_no_atencion->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_no_atencion = $total_ano_anterior_no_atencion->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_no_atencion = $total_ano_actual_no_atencion->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_desperfectos_tecnicos = $mes_ano_anterior_desperfectos_tecnicos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_desperfectos_tecnicos = $mes_ano_actual_desperfectos_tecnicos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_desperfectos_tecnicos = $acumulado_ano_anterior_desperfectos_tecnicos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_desperfectos_tecnicos = $acumulado_ano_actual_desperfectos_tecnicos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_desperfectos_tecnicos = $total_ano_anterior_desperfectos_tecnicos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_desperfectos_tecnicos = $total_ano_actual_desperfectos_tecnicos->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_sustancias = $mes_ano_anterior_sustancias->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_sustancias = $mes_ano_actual_sustancias->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_sustancias = $acumulado_ano_anterior_sustancias->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_sustancias = $acumulado_ano_actual_sustancias->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_sustancias = $total_ano_anterior_sustancias->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_sustancias = $total_ano_actual_sustancias->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_otros = $mes_ano_anterior_otros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_otros = $mes_ano_actual_otros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_otros = $acumulado_ano_anterior_otros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_otros = $acumulado_ano_actual_otros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_otros = $total_ano_anterior_otros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_otros = $total_ano_actual_otros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_via = $mes_ano_anterior_via->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_via = $mes_ano_actual_via->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_via = $acumulado_ano_anterior_via->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_via = $acumulado_ano_actual_via->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_via = $total_ano_anterior_via->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_via = $total_ano_actual_via->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_base = $mes_ano_anterior_base->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_base = $mes_ano_actual_base->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_base = $acumulado_ano_anterior_base->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_base = $acumulado_ano_actual_base->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_base = $total_ano_anterior_base->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_base = $total_ano_actual_base->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_anterior_kilometros = $mes_ano_anterior_kilometros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $mes_ano_actual_kilometros = $mes_ano_actual_kilometros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_anterior_kilometros = $acumulado_ano_anterior_kilometros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $acumulado_ano_actual_kilometros = $acumulado_ano_actual_kilometros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_anterior_kilometros = $total_ano_anterior_kilometros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
      $total_ano_actual_kilometros = $total_ano_actual_kilometros->where('mtdelegacion_territorials.delegacion_territorial', $request['delegacion']);
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Data">
    $data[0] = new stdClass();
    $data[0]->indicador = 'Accidentes imputables';
    $data[0]->mes_ano_anterior = $mes_ano_anterior_imputable;
    $data[0]->mes_ano_actual = $mes_ano_actual_imputable;
    $data[0]->mes_ano_diferencia = abs($mes_ano_anterior_imputable - $mes_ano_actual_imputable);
    $data[0]->acumulado_ano_anterior = $acumulado_ano_anterior_imputable;
    $data[0]->acumulado_ano_actual = $acumulado_ano_actual_imputable;
    $data[0]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_imputable - $acumulado_ano_actual_imputable);
    $data[0]->total_ano_anterior = $total_ano_anterior_imputable;
    $data[0]->total_ano_actual = $total_ano_actual_imputable;
    $data[0]->total_ano_diferencia = abs($total_ano_anterior_imputable - $total_ano_actual_imputable);
    $data[1] = new stdClass();
    $data[1]->indicador = 'Accidentes no imputables';
    $data[1]->mes_ano_anterior = $mes_ano_anterior_no_imputable;
    $data[1]->mes_ano_actual = $mes_ano_actual_no_imputable;
    $data[1]->mes_ano_diferencia = abs($mes_ano_anterior_no_imputable - $mes_ano_actual_no_imputable);
    $data[1]->acumulado_ano_anterior = $acumulado_ano_anterior_no_imputable;
    $data[1]->acumulado_ano_actual = $acumulado_ano_actual_no_imputable;
    $data[1]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_no_imputable - $acumulado_ano_actual_no_imputable);
    $data[1]->total_ano_anterior = $total_ano_anterior_no_imputable;
    $data[1]->total_ano_actual = $total_ano_actual_no_imputable;
    $data[1]->total_ano_diferencia = abs($total_ano_anterior_no_imputable - $total_ano_actual_no_imputable);
    $data[2] = new stdClass();
    $data[2]->indicador = 'Accidentes leves';
    $data[2]->mes_ano_anterior = $mes_ano_anterior_leves;
    $data[2]->mes_ano_actual = $mes_ano_actual_leves;
    $data[2]->mes_ano_diferencia = abs($mes_ano_anterior_leves - $mes_ano_actual_leves);
    $data[2]->acumulado_ano_anterior = $acumulado_ano_anterior_leves;
    $data[2]->acumulado_ano_actual = $acumulado_ano_actual_leves;
    $data[2]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_leves - $acumulado_ano_actual_leves);
    $data[2]->total_ano_anterior = $total_ano_anterior_leves;
    $data[2]->total_ano_actual = $total_ano_actual_leves;
    $data[2]->total_ano_diferencia = abs($total_ano_anterior_leves - $total_ano_actual_leves);
    $data[3] = new stdClass();
    $data[3]->indicador = 'Accidentes graves';
    $data[3]->mes_ano_anterior = $mes_ano_anterior_graves;
    $data[3]->mes_ano_actual = $mes_ano_actual_graves;
    $data[3]->mes_ano_diferencia = abs($mes_ano_anterior_graves - $mes_ano_actual_graves);
    $data[3]->acumulado_ano_anterior = $acumulado_ano_anterior_graves;
    $data[3]->acumulado_ano_actual = $acumulado_ano_actual_graves;
    $data[3]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_graves - $acumulado_ano_actual_graves);
    $data[3]->total_ano_anterior = $total_ano_anterior_graves;
    $data[3]->total_ano_actual = $total_ano_actual_graves;
    $data[3]->total_ano_diferencia = abs($total_ano_anterior_graves - $total_ano_actual_graves);
    $data[4] = new stdClass();
    $data[4]->indicador = 'Accidentes catastróficos';
    $data[4]->mes_ano_anterior = $mes_ano_anterior_catastroficos;
    $data[4]->mes_ano_actual = $mes_ano_actual_catastroficos;
    $data[4]->mes_ano_diferencia = abs($mes_ano_anterior_catastroficos - $mes_ano_actual_catastroficos);
    $data[4]->acumulado_ano_anterior = $acumulado_ano_anterior_catastroficos;
    $data[4]->acumulado_ano_actual = $acumulado_ano_actual_catastroficos;
    $data[4]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_catastroficos - $acumulado_ano_actual_catastroficos);
    $data[4]->total_ano_anterior = $total_ano_anterior_catastroficos;
    $data[4]->total_ano_actual = $total_ano_actual_catastroficos;
    $data[4]->total_ano_diferencia = abs($total_ano_anterior_catastroficos - $total_ano_actual_catastroficos);
    $data[5] = new stdClass();
    $data[5]->indicador = 'Heridos';
    $data[5]->mes_ano_anterior = $mes_ano_anterior_heridos;
    $data[5]->mes_ano_actual = $mes_ano_actual_heridos;
    $data[5]->mes_ano_diferencia = abs($mes_ano_anterior_heridos - $mes_ano_actual_heridos);
    $data[5]->acumulado_ano_anterior = $acumulado_ano_anterior_heridos;
    $data[5]->acumulado_ano_actual = $acumulado_ano_actual_heridos;
    $data[5]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_heridos - $acumulado_ano_actual_heridos);
    $data[5]->total_ano_anterior = $total_ano_anterior_heridos;
    $data[5]->total_ano_actual = $total_ano_actual_heridos;
    $data[5]->total_ano_diferencia = abs($total_ano_anterior_heridos - $total_ano_actual_heridos);
    $data[6] = new stdClass();
    $data[6]->indicador = 'Fallecidos';
    $data[6]->mes_ano_anterior = $mes_ano_anterior_fallecidos;
    $data[6]->mes_ano_actual = $mes_ano_actual_fallecidos;
    $data[6]->mes_ano_diferencia = abs($mes_ano_anterior_fallecidos - $mes_ano_actual_fallecidos);
    $data[6]->acumulado_ano_anterior = $acumulado_ano_anterior_fallecidos;
    $data[6]->acumulado_ano_actual = $acumulado_ano_actual_fallecidos;
    $data[6]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_fallecidos - $acumulado_ano_actual_fallecidos);
    $data[6]->total_ano_anterior = $total_ano_anterior_fallecidos;
    $data[6]->total_ano_actual = $total_ano_actual_fallecidos;
    $data[6]->total_ano_diferencia = abs($total_ano_anterior_fallecidos - $total_ano_actual_fallecidos);
    $data[7] = new stdClass();
    $data[7]->indicador = 'Pérdidas materiales';
    $data[7]->mes_ano_anterior = $mes_ano_anterior_perdidas_materiales;
    $data[7]->mes_ano_actual = $mes_ano_actual_perdidas_materiales;
    $data[7]->mes_ano_diferencia = abs($mes_ano_anterior_perdidas_materiales - $mes_ano_actual_perdidas_materiales);
    $data[7]->acumulado_ano_anterior = $acumulado_ano_anterior_perdidas_materiales;
    $data[7]->acumulado_ano_actual = $acumulado_ano_actual_perdidas_materiales;
    $data[7]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_perdidas_materiales - $acumulado_ano_actual_perdidas_materiales);
    $data[7]->total_ano_anterior = $total_ano_anterior_perdidas_materiales;
    $data[7]->total_ano_actual = $total_ano_actual_perdidas_materiales;
    $data[7]->total_ano_diferencia = abs($total_ano_anterior_perdidas_materiales - $total_ano_actual_perdidas_materiales);
    $data[8] = new stdClass();
    $data[8]->indicador = 'Accidentes con peatones';
    $data[8]->mes_ano_anterior = $mes_ano_anterior_peatones;
    $data[8]->mes_ano_actual = $mes_ano_actual_peatones;
    $data[8]->mes_ano_diferencia = abs($mes_ano_anterior_peatones - $mes_ano_actual_peatones);
    $data[8]->acumulado_ano_anterior = $acumulado_ano_anterior_peatones;
    $data[8]->acumulado_ano_actual = $acumulado_ano_actual_peatones;
    $data[8]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_peatones - $acumulado_ano_actual_peatones);
    $data[8]->total_ano_anterior = $total_ano_anterior_peatones;
    $data[8]->total_ano_actual = $total_ano_actual_peatones;
    $data[8]->total_ano_diferencia = abs($total_ano_anterior_peatones - $total_ano_actual_peatones);
    $data[9] = new stdClass();
    $data[9]->indicador = 'Accidentes con ciclos';
    $data[9]->mes_ano_anterior = $mes_ano_anterior_ciclos;
    $data[9]->mes_ano_actual = $mes_ano_actual_ciclos;
    $data[9]->mes_ano_diferencia = abs($mes_ano_anterior_ciclos - $mes_ano_actual_ciclos);
    $data[9]->acumulado_ano_anterior = $acumulado_ano_anterior_ciclos;
    $data[9]->acumulado_ano_actual = $acumulado_ano_actual_ciclos;
    $data[9]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_ciclos - $acumulado_ano_actual_ciclos);
    $data[9]->total_ano_anterior = $total_ano_anterior_ciclos;
    $data[9]->total_ano_actual = $total_ano_actual_ciclos;
    $data[9]->total_ano_diferencia = abs($total_ano_anterior_ciclos - $total_ano_actual_ciclos);
    $data[10] = new stdClass();
    $data[10]->indicador = 'Accidentes con animales';
    $data[10]->mes_ano_anterior = $mes_ano_anterior_animales;
    $data[10]->mes_ano_actual = $mes_ano_actual_animales;
    $data[10]->mes_ano_diferencia = abs($mes_ano_anterior_animales - $mes_ano_actual_animales);
    $data[10]->acumulado_ano_anterior = $acumulado_ano_anterior_animales;
    $data[10]->acumulado_ano_actual = $acumulado_ano_actual_animales;
    $data[10]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_animales - $acumulado_ano_actual_animales);
    $data[10]->total_ano_anterior = $total_ano_anterior_animales;
    $data[10]->total_ano_actual = $total_ano_actual_animales;
    $data[10]->total_ano_diferencia = abs($total_ano_anterior_animales - $total_ano_actual_animales);
    $data[11] = new stdClass();
    $data[11]->indicador = 'Exceso de velocidad';
    $data[11]->mes_ano_anterior = $mes_ano_anterior_exceso_velocidad;
    $data[11]->mes_ano_actual = $mes_ano_actual_exceso_velocidad;
    $data[11]->mes_ano_diferencia = abs($mes_ano_anterior_exceso_velocidad - $mes_ano_actual_exceso_velocidad);
    $data[11]->acumulado_ano_anterior = $acumulado_ano_anterior_exceso_velocidad;
    $data[11]->acumulado_ano_actual = $acumulado_ano_actual_exceso_velocidad;
    $data[11]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_exceso_velocidad - $acumulado_ano_actual_exceso_velocidad);
    $data[11]->total_ano_anterior = $total_ano_anterior_exceso_velocidad;
    $data[11]->total_ano_actual = $total_ano_actual_exceso_velocidad;
    $data[11]->total_ano_diferencia = abs($total_ano_anterior_exceso_velocidad - $total_ano_actual_exceso_velocidad);
    $data[12] = new stdClass();
    $data[12]->indicador = 'No atender el control del vehículo';
    $data[12]->mes_ano_anterior = $mes_ano_anterior_no_atencion;
    $data[12]->mes_ano_actual = $mes_ano_actual_no_atencion;
    $data[12]->mes_ano_diferencia = abs($mes_ano_anterior_no_atencion - $mes_ano_actual_no_atencion);
    $data[12]->acumulado_ano_anterior = $acumulado_ano_anterior_no_atencion;
    $data[12]->acumulado_ano_actual = $acumulado_ano_actual_no_atencion;
    $data[12]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_no_atencion - $acumulado_ano_actual_no_atencion);
    $data[12]->total_ano_anterior = $total_ano_anterior_no_atencion;
    $data[12]->total_ano_actual = $total_ano_actual_no_atencion;
    $data[12]->total_ano_diferencia = abs($total_ano_anterior_no_atencion - $total_ano_actual_no_atencion);
    $data[13] = new stdClass();
    $data[13]->indicador = 'Desperfectos técnicos';
    $data[13]->mes_ano_anterior = $mes_ano_anterior_desperfectos_tecnicos;
    $data[13]->mes_ano_actual = $mes_ano_actual_desperfectos_tecnicos;
    $data[13]->mes_ano_diferencia = abs($mes_ano_anterior_desperfectos_tecnicos - $mes_ano_actual_desperfectos_tecnicos);
    $data[13]->acumulado_ano_anterior = $acumulado_ano_anterior_desperfectos_tecnicos;
    $data[13]->acumulado_ano_actual = $acumulado_ano_actual_desperfectos_tecnicos;
    $data[13]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_desperfectos_tecnicos - $acumulado_ano_actual_desperfectos_tecnicos);
    $data[13]->total_ano_anterior = $total_ano_anterior_desperfectos_tecnicos;
    $data[13]->total_ano_actual = $total_ano_actual_desperfectos_tecnicos;
    $data[13]->total_ano_diferencia = abs($total_ano_anterior_desperfectos_tecnicos - $total_ano_actual_desperfectos_tecnicos);
    $data[14] = new stdClass();
    $data[14]->indicador = 'Ingestión de bebidas alcohólicas y otras sustancias';
    $data[14]->mes_ano_anterior = $mes_ano_anterior_sustancias;
    $data[14]->mes_ano_actual = $mes_ano_actual_sustancias;
    $data[14]->mes_ano_diferencia = abs($mes_ano_anterior_sustancias - $mes_ano_actual_sustancias);
    $data[14]->acumulado_ano_anterior = $acumulado_ano_anterior_sustancias;
    $data[14]->acumulado_ano_actual = $acumulado_ano_actual_sustancias;
    $data[14]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_sustancias - $acumulado_ano_actual_sustancias);
    $data[14]->total_ano_anterior = $total_ano_anterior_sustancias;
    $data[14]->total_ano_actual = $total_ano_actual_sustancias;
    $data[14]->total_ano_diferencia = abs($total_ano_anterior_sustancias - $total_ano_actual_sustancias);
    $data[15] = new stdClass();
    $data[15]->indicador = 'Otros';
    $data[15]->mes_ano_anterior = $mes_ano_anterior_otros;
    $data[15]->mes_ano_actual = $mes_ano_actual_otros;
    $data[15]->mes_ano_diferencia = abs($mes_ano_anterior_otros - $mes_ano_actual_otros);
    $data[15]->acumulado_ano_anterior = $acumulado_ano_anterior_otros;
    $data[15]->acumulado_ano_actual = $acumulado_ano_actual_otros;
    $data[15]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_otros - $acumulado_ano_actual_otros);
    $data[15]->total_ano_anterior = $total_ano_anterior_otros;
    $data[15]->total_ano_actual = $total_ano_actual_otros;
    $data[15]->total_ano_diferencia = abs($total_ano_anterior_otros - $total_ano_actual_otros);
    $data[16] = new stdClass();
    $data[16]->indicador = 'Total de accidentes en la vía';
    $data[16]->mes_ano_anterior = $mes_ano_anterior_via;
    $data[16]->mes_ano_actual = $mes_ano_actual_via;
    $data[16]->mes_ano_diferencia = abs($mes_ano_anterior_via - $mes_ano_actual_via);
    $data[16]->acumulado_ano_anterior = $acumulado_ano_anterior_via;
    $data[16]->acumulado_ano_actual = $acumulado_ano_actual_via;
    $data[16]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_via - $acumulado_ano_actual_via);
    $data[16]->total_ano_anterior = $total_ano_anterior_via;
    $data[16]->total_ano_actual = $total_ano_actual_via;
    $data[16]->total_ano_diferencia = abs($total_ano_anterior_via - $total_ano_actual_via);
    $data[17] = new stdClass();
    $data[17]->indicador = 'Total de accidentes en base';
    $data[17]->mes_ano_anterior = $mes_ano_anterior_base;
    $data[17]->mes_ano_actual = $mes_ano_actual_base;
    $data[17]->mes_ano_diferencia = abs($mes_ano_anterior_base - $mes_ano_actual_base);
    $data[17]->acumulado_ano_anterior = $acumulado_ano_anterior_base;
    $data[17]->acumulado_ano_actual = $acumulado_ano_actual_base;
    $data[17]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_base - $acumulado_ano_actual_base);
    $data[17]->total_ano_anterior = $total_ano_anterior_base;
    $data[17]->total_ano_actual = $total_ano_actual_base;
    $data[17]->total_ano_diferencia = abs($total_ano_anterior_base - $total_ano_actual_base);
    $data[18] = new stdClass();
    $data[18]->indicador = 'Kilómetros recorridos';
    $data[18]->mes_ano_anterior = $mes_ano_anterior_kilometros;
    $data[18]->mes_ano_actual = $mes_ano_actual_kilometros;
    $data[18]->mes_ano_diferencia = abs($mes_ano_anterior_kilometros - $mes_ano_actual_kilometros);
    $data[18]->acumulado_ano_anterior = $acumulado_ano_anterior_kilometros;
    $data[18]->acumulado_ano_actual = $acumulado_ano_actual_kilometros;
    $data[18]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_kilometros - $acumulado_ano_actual_kilometros);
    $data[18]->total_ano_anterior = $total_ano_anterior_kilometros;
    $data[18]->total_ano_actual = $total_ano_actual_kilometros;
    $data[18]->total_ano_diferencia = abs($total_ano_anterior_kilometros - $total_ano_actual_kilometros);
    $data[19] = new stdClass();
    $data[19]->indicador = 'Índice de accidentalidad';
    $data[19]->mes_ano_anterior = round($this->warningdivisionbyzero($mes_ano_anterior_cant_accidentes, $mes_ano_anterior_kilometros), 2);
    $data[19]->mes_ano_actual = round($this->warningdivisionbyzero($mes_ano_actual_cant_accidentes, $mes_ano_actual_kilometros), 2);
    $data[19]->mes_ano_diferencia = abs(round($this->warningdivisionbyzero($mes_ano_anterior_cant_accidentes, $mes_ano_anterior_kilometros), 2) - round($this->warningdivisionbyzero($mes_ano_actual_cant_accidentes, $mes_ano_actual_kilometros), 2));
    $data[19]->acumulado_ano_anterior = round($this->warningdivisionbyzero($acumulado_ano_anterior_cant_accidentes, $acumulado_ano_anterior_kilometros), 2);
    $data[19]->acumulado_ano_actual = round($this->warningdivisionbyzero($acumulado_ano_actual_cant_accidentes, $acumulado_ano_actual_kilometros), 2);
    $data[19]->acumulado_ano_diferencia = abs(round($this->warningdivisionbyzero($acumulado_ano_anterior_cant_accidentes, $acumulado_ano_anterior_kilometros), 2) - round($this->warningdivisionbyzero($acumulado_ano_actual_cant_accidentes, $acumulado_ano_actual_kilometros), 2));
    $data[19]->total_ano_anterior = round($this->warningdivisionbyzero($total_ano_anterior_cant_accidentes, $total_ano_anterior_kilometros), 2);
    $data[19]->total_ano_actual = round($this->warningdivisionbyzero($total_ano_actual_cant_accidentes, $total_ano_actual_kilometros), 2);
    $data[19]->total_ano_diferencia = abs(round($this->warningdivisionbyzero($total_ano_anterior_cant_accidentes, $total_ano_anterior_kilometros), 2) - round($this->warningdivisionbyzero($total_ano_actual_cant_accidentes, $total_ano_actual_kilometros), 2));
    //</editor-fold>

    return $data;
  }

// Reporte -> Exportar a pdf el resultado del reporte mostrar por cada indicador la información estadística de accidentalidad
  public function reporte_accidentalidad_pdf($request)
  {
    $ano_anterior = date('Y') - 1;
    $ano_actual = date('Y');

    //<editor-fold defaultstate="collapsed" desc="Mes Año Anterior">
    $mes_ano_anterior_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])
      ->where('imputable', '1')->count();

    $mes_ano_anterior_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('imputable', '0')->count();

    $mes_ano_anterior_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $mes_ano_anterior_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $mes_ano_anterior_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $mes_ano_anterior_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('herido');

    $mes_ano_anterior_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('fallecido');

    $mes_ano_anterior_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('perdidas_materiales');

    $mes_ano_anterior_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '1')->count();

    $mes_ano_anterior_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $mes_ano_anterior_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $mes_ano_anterior_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $mes_ano_anterior_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $mes_ano_anterior_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $mes_ano_anterior_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $mes_ano_anterior_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $mes_ano_anterior_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_via', '1')->count();

    $mes_ano_anterior_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_base', '1')->count();

    $mes_ano_anterior_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_anterior)
      ->where('mes', '=', $request['mes'])->sum('km_recorridos');

    $mes_ano_anterior_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Mes Año Actual">
    $mes_ano_actual_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('imputable', '1')->count();

    $mes_ano_actual_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('imputable', '=','0')->count();

    $mes_ano_actual_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $mes_ano_actual_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $mes_ano_actual_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $mes_ano_actual_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('herido');

    $mes_ano_actual_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('fallecido');

    $mes_ano_actual_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->sum('perdidas_materiales');

    $mes_ano_actual_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '1')->count();


    $mes_ano_actual_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $mes_ano_actual_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $mes_ano_actual_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $mes_ano_actual_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $mes_ano_actual_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $mes_ano_actual_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $mes_ano_actual_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $mes_ano_actual_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_via', '1')->count();

    $mes_ano_actual_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->where('accidente_base', '1')->count();

    $mes_ano_actual_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_actual)
      ->where('mes', '=', $request['mes'])->sum('km_recorridos');

    $mes_ano_actual_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Acumulado Año Anterior">
    $acumulado_ano_anterior_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '1')->count();

    $acumulado_ano_anterior_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '0')->count();

    $acumulado_ano_anterior_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $acumulado_ano_anterior_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $acumulado_ano_anterior_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $acumulado_ano_anterior_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('herido');

    $acumulado_ano_anterior_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('fallecido');

    $acumulado_ano_anterior_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('perdidas_materiales');

    $acumulado_ano_anterior_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '1')->count();

    $acumulado_ano_anterior_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $acumulado_ano_anterior_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $acumulado_ano_anterior_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $acumulado_ano_anterior_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $acumulado_ano_anterior_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $acumulado_ano_anterior_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $acumulado_ano_anterior_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $acumulado_ano_anterior_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_via', '1')->count();

    $acumulado_ano_anterior_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_base', '1')->count();

    $acumulado_ano_anterior_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_anterior)
      ->where('mes', '<=', $request['mes'])->sum('km_recorridos');

    $acumulado_ano_anterior_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Acumulado Año Actual">
    $acumulado_ano_actual_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '1')->count();

    $acumulado_ano_actual_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('imputable', '0')->count();

    $acumulado_ano_actual_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '1')->count();

    $acumulado_ano_actual_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '2')->count();

    $acumulado_ano_actual_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('clasificacion_accidente_id', '3')->count();

    $acumulado_ano_actual_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('herido');

    $acumulado_ano_actual_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('fallecido');

    $acumulado_ano_actual_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->sum('perdidas_materiales');

    $acumulado_ano_actual_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '1')->count();

    $acumulado_ano_actual_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '2')->count();

    $acumulado_ano_actual_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('victima_accidentes_id', '3')->count();

    $acumulado_ano_actual_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '1')->count();

    $acumulado_ano_actual_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '2')->count();

    $acumulado_ano_actual_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '3')->count();

    $acumulado_ano_actual_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '4')->count();

    $acumulado_ano_actual_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('causa_accidente_id', '5')->count();

    $acumulado_ano_actual_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_via', '1')->count();

    $acumulado_ano_actual_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->where('accidente_base', '1')->count();

    $acumulado_ano_actual_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_actual)
      ->where('mes', '<=', $request['mes'])->sum('km_recorridos');

    $acumulado_ano_actual_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)
      ->whereMonth('mtgestionaraccidentes.fecha', '<=', $request['mes'])->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Total Año Anterior">
    $total_ano_anterior_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('imputable', '1')->count();

    $total_ano_anterior_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('imputable', '0')->count();

    $total_ano_anterior_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('clasificacion_accidente_id', '1')->count();

    $total_ano_anterior_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('clasificacion_accidente_id', '2')->count();

    $total_ano_anterior_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('clasificacion_accidente_id', '3')->count();

    $total_ano_anterior_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->sum('herido');

    $total_ano_anterior_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->sum('fallecido');

    $total_ano_anterior_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->sum('perdidas_materiales');

    $total_ano_anterior_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('victima_accidentes_id', '1')->count();

    $total_ano_anterior_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('victima_accidentes_id', '2')->count();

    $total_ano_anterior_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('victima_accidentes_id', '3')->count();

    $total_ano_anterior_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '1')->count();

    $total_ano_anterior_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '2')->count();

    $total_ano_anterior_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '3')->count();

    $total_ano_anterior_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '4')->count();

    $total_ano_anterior_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('causa_accidente_id', '5')->count();

    $total_ano_anterior_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('accidente_via', '1')->count();

    $total_ano_anterior_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->where('accidente_base', '1')->count();

    $total_ano_anterior_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_anterior)->sum('km_recorridos');

    $total_ano_anterior_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_anterior)->count();
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Total Año Actual">
    $total_ano_actual_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('imputable', '1')->count();

    $total_ano_actual_no_imputable = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('imputable', '0')->count();

    $total_ano_actual_leves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('clasificacion_accidente_id', '1')->count();

    $total_ano_actual_graves = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('clasificacion_accidente_id', '2')->count();

    $total_ano_actual_catastroficos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('clasificacion_accidente_id', '3')->count();

    $total_ano_actual_heridos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->sum('herido');

    $total_ano_actual_fallecidos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->sum('fallecido');

    $total_ano_actual_perdidas_materiales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->sum('perdidas_materiales');

    $total_ano_actual_peatones = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('victima_accidentes_id', '1')->count();

    $total_ano_actual_ciclos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('victima_accidentes_id', '2')->count();

    $total_ano_actual_animales = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('victima_accidentes_id', '3')->count();

    $total_ano_actual_exceso_velocidad = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '1')->count();

    $total_ano_actual_no_atencion = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '2')->count();

    $total_ano_actual_desperfectos_tecnicos = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '3')->count();

    $total_ano_actual_sustancias = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '4')->count();

    $total_ano_actual_otros = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('causa_accidente_id', '5')->count();

    $total_ano_actual_via = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('accidente_via', '1')->count();

    $total_ano_actual_base = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->where('accidente_base', '1')->count();

    $total_ano_actual_kilometros = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtmedio_transportes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia',)
      ->where('anno', '=', $ano_actual)->sum('km_recorridos');

    $total_ano_actual_cant_accidentes = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->join('mtinstalacion', 'mtgestionaraccidentes.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtosde', 'mtinstalacion.osde_id', '=', 'mtosde.id')
      ->join('mtprovincia', 'mtinstalacion.provincia_id', '=', 'mtprovincia.id')
      ->join('mtclasificacionaccidentes', 'mtgestionaraccidentes.clasificacion_accidente_id', '=', 'mtclasificacionaccidentes.id')
      ->join('mtvictimaaccidentes', 'mtgestionaraccidentes.victima_accidentes_id', '=', 'mtvictimaaccidentes.id')
      ->join('mtcausaaccidentes', 'mtgestionaraccidentes.causa_accidente_id', '=', 'mtcausaaccidentes.id')
      ->select('mtosde.nombre as osde', 'mtinstalacion.nombre as instalacion', 'mtprovincia.nombre as provincia')
      ->whereYear('mtgestionaraccidentes.fecha', '=', $ano_actual)->count();
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Data">
    $data[0] = new stdClass();
    $data[0]->indicador = 'Accidentes imputables';
    $data[0]->mes_ano_anterior = $mes_ano_anterior_imputable;
    $data[0]->mes_ano_actual = $mes_ano_actual_imputable;
    $data[0]->mes_ano_diferencia = abs($mes_ano_anterior_imputable - $mes_ano_actual_imputable);
    $data[0]->acumulado_ano_anterior = $acumulado_ano_anterior_imputable;
    $data[0]->acumulado_ano_actual = $acumulado_ano_actual_imputable;
    $data[0]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_imputable - $acumulado_ano_actual_imputable);
    $data[0]->total_ano_anterior = $total_ano_anterior_imputable;
    $data[0]->total_ano_actual = $total_ano_actual_imputable;
    $data[0]->total_ano_diferencia = abs($total_ano_anterior_imputable - $total_ano_actual_imputable);
    $data[1] = new stdClass();
    $data[1]->indicador = 'Accidentes no imputables';
    $data[1]->mes_ano_anterior = $mes_ano_anterior_no_imputable;
    $data[1]->mes_ano_actual = $mes_ano_actual_no_imputable;
    $data[1]->mes_ano_diferencia = abs($mes_ano_anterior_no_imputable - $mes_ano_actual_no_imputable);
    $data[1]->acumulado_ano_anterior = $acumulado_ano_anterior_no_imputable;
    $data[1]->acumulado_ano_actual = $acumulado_ano_actual_no_imputable;
    $data[1]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_no_imputable - $acumulado_ano_actual_no_imputable);
    $data[1]->total_ano_anterior = $total_ano_anterior_no_imputable;
    $data[1]->total_ano_actual = $total_ano_actual_no_imputable;
    $data[1]->total_ano_diferencia = abs($total_ano_anterior_no_imputable - $total_ano_actual_no_imputable);
    $data[2] = new stdClass();
    $data[2]->indicador = 'Accidentes leves';
    $data[2]->mes_ano_anterior = $mes_ano_anterior_leves;
    $data[2]->mes_ano_actual = $mes_ano_actual_leves;
    $data[2]->mes_ano_diferencia = abs($mes_ano_anterior_leves - $mes_ano_actual_leves);
    $data[2]->acumulado_ano_anterior = $acumulado_ano_anterior_leves;
    $data[2]->acumulado_ano_actual = $acumulado_ano_actual_leves;
    $data[2]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_leves - $acumulado_ano_actual_leves);
    $data[2]->total_ano_anterior = $total_ano_anterior_leves;
    $data[2]->total_ano_actual = $total_ano_actual_leves;
    $data[2]->total_ano_diferencia = abs($total_ano_anterior_leves - $total_ano_actual_leves);
    $data[3] = new stdClass();
    $data[3]->indicador = 'Accidentes graves';
    $data[3]->mes_ano_anterior = $mes_ano_anterior_graves;
    $data[3]->mes_ano_actual = $mes_ano_actual_graves;
    $data[3]->mes_ano_diferencia = abs($mes_ano_anterior_graves - $mes_ano_actual_graves);
    $data[3]->acumulado_ano_anterior = $acumulado_ano_anterior_graves;
    $data[3]->acumulado_ano_actual = $acumulado_ano_actual_graves;
    $data[3]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_graves - $acumulado_ano_actual_graves);
    $data[3]->total_ano_anterior = $total_ano_anterior_graves;
    $data[3]->total_ano_actual = $total_ano_actual_graves;
    $data[3]->total_ano_diferencia = abs($total_ano_anterior_graves - $total_ano_actual_graves);
    $data[4] = new stdClass();
    $data[4]->indicador = 'Accidentes catastróficos';
    $data[4]->mes_ano_anterior = $mes_ano_anterior_catastroficos;
    $data[4]->mes_ano_actual = $mes_ano_actual_catastroficos;
    $data[4]->mes_ano_diferencia = abs($mes_ano_anterior_catastroficos - $mes_ano_actual_catastroficos);
    $data[4]->acumulado_ano_anterior = $acumulado_ano_anterior_catastroficos;
    $data[4]->acumulado_ano_actual = $acumulado_ano_actual_catastroficos;
    $data[4]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_catastroficos - $acumulado_ano_actual_catastroficos);
    $data[4]->total_ano_anterior = $total_ano_anterior_catastroficos;
    $data[4]->total_ano_actual = $total_ano_actual_catastroficos;
    $data[4]->total_ano_diferencia = abs($total_ano_anterior_catastroficos - $total_ano_actual_catastroficos);
    $data[5] = new stdClass();
    $data[5]->indicador = 'Heridos';
    $data[5]->mes_ano_anterior = $mes_ano_anterior_heridos;
    $data[5]->mes_ano_actual = $mes_ano_actual_heridos;
    $data[5]->mes_ano_diferencia = abs($mes_ano_anterior_heridos - $mes_ano_actual_heridos);
    $data[5]->acumulado_ano_anterior = $acumulado_ano_anterior_heridos;
    $data[5]->acumulado_ano_actual = $acumulado_ano_actual_heridos;
    $data[5]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_heridos - $acumulado_ano_actual_heridos);
    $data[5]->total_ano_anterior = $total_ano_anterior_heridos;
    $data[5]->total_ano_actual = $total_ano_actual_heridos;
    $data[5]->total_ano_diferencia = abs($total_ano_anterior_heridos - $total_ano_actual_heridos);
    $data[6] = new stdClass();
    $data[6]->indicador = 'Fallecidos';
    $data[6]->mes_ano_anterior = $mes_ano_anterior_fallecidos;
    $data[6]->mes_ano_actual = $mes_ano_actual_fallecidos;
    $data[6]->mes_ano_diferencia = abs($mes_ano_anterior_fallecidos - $mes_ano_actual_fallecidos);
    $data[6]->acumulado_ano_anterior = $acumulado_ano_anterior_fallecidos;
    $data[6]->acumulado_ano_actual = $acumulado_ano_actual_fallecidos;
    $data[6]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_fallecidos - $acumulado_ano_actual_fallecidos);
    $data[6]->total_ano_anterior = $total_ano_anterior_fallecidos;
    $data[6]->total_ano_actual = $total_ano_actual_fallecidos;
    $data[6]->total_ano_diferencia = abs($total_ano_anterior_fallecidos - $total_ano_actual_fallecidos);
    $data[7] = new stdClass();
    $data[7]->indicador = 'Pérdidas materiales';
    $data[7]->mes_ano_anterior = $mes_ano_anterior_perdidas_materiales;
    $data[7]->mes_ano_actual = $mes_ano_actual_perdidas_materiales;
    $data[7]->mes_ano_diferencia = abs($mes_ano_anterior_perdidas_materiales - $mes_ano_actual_perdidas_materiales);
    $data[7]->acumulado_ano_anterior = $acumulado_ano_anterior_perdidas_materiales;
    $data[7]->acumulado_ano_actual = $acumulado_ano_actual_perdidas_materiales;
    $data[7]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_perdidas_materiales - $acumulado_ano_actual_perdidas_materiales);
    $data[7]->total_ano_anterior = $total_ano_anterior_perdidas_materiales;
    $data[7]->total_ano_actual = $total_ano_actual_perdidas_materiales;
    $data[7]->total_ano_diferencia = abs($total_ano_anterior_perdidas_materiales - $total_ano_actual_perdidas_materiales);
    $data[8] = new stdClass();
    $data[8]->indicador = 'Accidentes con peatones';
    $data[8]->mes_ano_anterior = $mes_ano_anterior_peatones;
    $data[8]->mes_ano_actual = $mes_ano_actual_peatones;
    $data[8]->mes_ano_diferencia = abs($mes_ano_anterior_peatones - $mes_ano_actual_peatones);
    $data[8]->acumulado_ano_anterior = $acumulado_ano_anterior_peatones;
    $data[8]->acumulado_ano_actual = $acumulado_ano_actual_peatones;
    $data[8]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_peatones - $acumulado_ano_actual_peatones);
    $data[8]->total_ano_anterior = $total_ano_anterior_peatones;
    $data[8]->total_ano_actual = $total_ano_actual_peatones;
    $data[8]->total_ano_diferencia = abs($total_ano_anterior_peatones - $total_ano_actual_peatones);
    $data[9] = new stdClass();
    $data[9]->indicador = 'Accidentes con ciclos';
    $data[9]->mes_ano_anterior = $mes_ano_anterior_ciclos;
    $data[9]->mes_ano_actual = $mes_ano_actual_ciclos;
    $data[9]->mes_ano_diferencia = abs($mes_ano_anterior_ciclos - $mes_ano_actual_ciclos);
    $data[9]->acumulado_ano_anterior = $acumulado_ano_anterior_ciclos;
    $data[9]->acumulado_ano_actual = $acumulado_ano_actual_ciclos;
    $data[9]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_ciclos - $acumulado_ano_actual_ciclos);
    $data[9]->total_ano_anterior = $total_ano_anterior_ciclos;
    $data[9]->total_ano_actual = $total_ano_actual_ciclos;
    $data[9]->total_ano_diferencia = abs($total_ano_anterior_ciclos - $total_ano_actual_ciclos);
    $data[10] = new stdClass();
    $data[10]->indicador = 'Accidentes con animales';
    $data[10]->mes_ano_anterior = $mes_ano_anterior_animales;
    $data[10]->mes_ano_actual = $mes_ano_actual_animales;
    $data[10]->mes_ano_diferencia = abs($mes_ano_anterior_animales - $mes_ano_actual_animales);
    $data[10]->acumulado_ano_anterior = $acumulado_ano_anterior_animales;
    $data[10]->acumulado_ano_actual = $acumulado_ano_actual_animales;
    $data[10]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_animales - $acumulado_ano_actual_animales);
    $data[10]->total_ano_anterior = $total_ano_anterior_animales;
    $data[10]->total_ano_actual = $total_ano_actual_animales;
    $data[10]->total_ano_diferencia = abs($total_ano_anterior_animales - $total_ano_actual_animales);
    $data[11] = new stdClass();
    $data[11]->indicador = 'Exceso de velocidad';
    $data[11]->mes_ano_anterior = $mes_ano_anterior_exceso_velocidad;
    $data[11]->mes_ano_actual = $mes_ano_actual_exceso_velocidad;
    $data[11]->mes_ano_diferencia = abs($mes_ano_anterior_exceso_velocidad - $mes_ano_actual_exceso_velocidad);
    $data[11]->acumulado_ano_anterior = $acumulado_ano_anterior_exceso_velocidad;
    $data[11]->acumulado_ano_actual = $acumulado_ano_actual_exceso_velocidad;
    $data[11]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_exceso_velocidad - $acumulado_ano_actual_exceso_velocidad);
    $data[11]->total_ano_anterior = $total_ano_anterior_exceso_velocidad;
    $data[11]->total_ano_actual = $total_ano_actual_exceso_velocidad;
    $data[11]->total_ano_diferencia = abs($total_ano_anterior_exceso_velocidad - $total_ano_actual_exceso_velocidad);
    $data[12] = new stdClass();
    $data[12]->indicador = 'No atender el control del vehículo';
    $data[12]->mes_ano_anterior = $mes_ano_anterior_no_atencion;
    $data[12]->mes_ano_actual = $mes_ano_actual_no_atencion;
    $data[12]->mes_ano_diferencia = abs($mes_ano_anterior_no_atencion - $mes_ano_actual_no_atencion);
    $data[12]->acumulado_ano_anterior = $acumulado_ano_anterior_no_atencion;
    $data[12]->acumulado_ano_actual = $acumulado_ano_actual_no_atencion;
    $data[12]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_no_atencion - $acumulado_ano_actual_no_atencion);
    $data[12]->total_ano_anterior = $total_ano_anterior_no_atencion;
    $data[12]->total_ano_actual = $total_ano_actual_no_atencion;
    $data[12]->total_ano_diferencia = abs($total_ano_anterior_no_atencion - $total_ano_actual_no_atencion);
    $data[13] = new stdClass();
    $data[13]->indicador = 'Desperfectos técnicos';
    $data[13]->mes_ano_anterior = $mes_ano_anterior_desperfectos_tecnicos;
    $data[13]->mes_ano_actual = $mes_ano_actual_desperfectos_tecnicos;
    $data[13]->mes_ano_diferencia = abs($mes_ano_anterior_desperfectos_tecnicos - $mes_ano_actual_desperfectos_tecnicos);
    $data[13]->acumulado_ano_anterior = $acumulado_ano_anterior_desperfectos_tecnicos;
    $data[13]->acumulado_ano_actual = $acumulado_ano_actual_desperfectos_tecnicos;
    $data[13]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_desperfectos_tecnicos - $acumulado_ano_actual_desperfectos_tecnicos);
    $data[13]->total_ano_anterior = $total_ano_anterior_desperfectos_tecnicos;
    $data[13]->total_ano_actual = $total_ano_actual_desperfectos_tecnicos;
    $data[13]->total_ano_diferencia = abs($total_ano_anterior_desperfectos_tecnicos - $total_ano_actual_desperfectos_tecnicos);
    $data[14] = new stdClass();
    $data[14]->indicador = 'Ingestión de bebidas alcohólicas y otras sustancias';
    $data[14]->mes_ano_anterior = $mes_ano_anterior_sustancias;
    $data[14]->mes_ano_actual = $mes_ano_actual_sustancias;
    $data[14]->mes_ano_diferencia = abs($mes_ano_anterior_sustancias - $mes_ano_actual_sustancias);
    $data[14]->acumulado_ano_anterior = $acumulado_ano_anterior_sustancias;
    $data[14]->acumulado_ano_actual = $acumulado_ano_actual_sustancias;
    $data[14]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_sustancias - $acumulado_ano_actual_sustancias);
    $data[14]->total_ano_anterior = $total_ano_anterior_sustancias;
    $data[14]->total_ano_actual = $total_ano_actual_sustancias;
    $data[14]->total_ano_diferencia = abs($total_ano_anterior_sustancias - $total_ano_actual_sustancias);
    $data[15] = new stdClass();
    $data[15]->indicador = 'Otros';
    $data[15]->mes_ano_anterior = $mes_ano_anterior_otros;
    $data[15]->mes_ano_actual = $mes_ano_actual_otros;
    $data[15]->mes_ano_diferencia = abs($mes_ano_anterior_otros - $mes_ano_actual_otros);
    $data[15]->acumulado_ano_anterior = $acumulado_ano_anterior_otros;
    $data[15]->acumulado_ano_actual = $acumulado_ano_actual_otros;
    $data[15]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_otros - $acumulado_ano_actual_otros);
    $data[15]->total_ano_anterior = $total_ano_anterior_otros;
    $data[15]->total_ano_actual = $total_ano_actual_otros;
    $data[15]->total_ano_diferencia = abs($total_ano_anterior_otros - $total_ano_actual_otros);
    $data[16] = new stdClass();
    $data[16]->indicador = 'Total de accidentes en la vía';
    $data[16]->mes_ano_anterior = $mes_ano_anterior_via;
    $data[16]->mes_ano_actual = $mes_ano_actual_via;
    $data[16]->mes_ano_diferencia = abs($mes_ano_anterior_via - $mes_ano_actual_via);
    $data[16]->acumulado_ano_anterior = $acumulado_ano_anterior_via;
    $data[16]->acumulado_ano_actual = $acumulado_ano_actual_via;
    $data[16]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_via - $acumulado_ano_actual_via);
    $data[16]->total_ano_anterior = $total_ano_anterior_via;
    $data[16]->total_ano_actual = $total_ano_actual_via;
    $data[16]->total_ano_diferencia = abs($total_ano_anterior_via - $total_ano_actual_via);
    $data[17] = new stdClass();
    $data[17]->indicador = 'Total de accidentes en base';
    $data[17]->mes_ano_anterior = $mes_ano_anterior_base;
    $data[17]->mes_ano_actual = $mes_ano_actual_base;
    $data[17]->mes_ano_diferencia = abs($mes_ano_anterior_base - $mes_ano_actual_base);
    $data[17]->acumulado_ano_anterior = $acumulado_ano_anterior_base;
    $data[17]->acumulado_ano_actual = $acumulado_ano_actual_base;
    $data[17]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_base - $acumulado_ano_actual_base);
    $data[17]->total_ano_anterior = $total_ano_anterior_base;
    $data[17]->total_ano_actual = $total_ano_actual_base;
    $data[17]->total_ano_diferencia = abs($total_ano_anterior_base - $total_ano_actual_base);
    $data[18] = new stdClass();
    $data[18]->indicador = 'Kilómetros recorridos';
    $data[18]->mes_ano_anterior = $mes_ano_anterior_kilometros;
    $data[18]->mes_ano_actual = $mes_ano_actual_kilometros;
    $data[18]->mes_ano_diferencia = abs($mes_ano_anterior_kilometros - $mes_ano_actual_kilometros);
    $data[18]->acumulado_ano_anterior = $acumulado_ano_anterior_kilometros;
    $data[18]->acumulado_ano_actual = $acumulado_ano_actual_kilometros;
    $data[18]->acumulado_ano_diferencia = abs($acumulado_ano_anterior_kilometros - $acumulado_ano_actual_kilometros);
    $data[18]->total_ano_anterior = $total_ano_anterior_kilometros;
    $data[18]->total_ano_actual = $total_ano_actual_kilometros;
    $data[18]->total_ano_diferencia = abs($total_ano_anterior_kilometros - $total_ano_actual_kilometros);
    $data[19] = new stdClass();
    $data[19]->indicador = 'Índice de accidentalidad';
    $data[19]->mes_ano_anterior = round($this->warningdivisionbyzero($mes_ano_anterior_cant_accidentes, $mes_ano_anterior_kilometros), 2);
    $data[19]->mes_ano_actual = round($this->warningdivisionbyzero($mes_ano_actual_cant_accidentes, $mes_ano_actual_kilometros), 2);
    $data[19]->mes_ano_diferencia = abs(round($this->warningdivisionbyzero($mes_ano_anterior_cant_accidentes, $mes_ano_anterior_kilometros), 2) - round($this->warningdivisionbyzero($mes_ano_actual_cant_accidentes, $mes_ano_actual_kilometros), 2));
    $data[19]->acumulado_ano_anterior = round($this->warningdivisionbyzero($acumulado_ano_anterior_cant_accidentes, $acumulado_ano_anterior_kilometros), 2);
    $data[19]->acumulado_ano_actual = round($this->warningdivisionbyzero($acumulado_ano_actual_cant_accidentes, $acumulado_ano_actual_kilometros), 2);
    $data[19]->acumulado_ano_diferencia = abs(round($this->warningdivisionbyzero($acumulado_ano_anterior_cant_accidentes, $acumulado_ano_anterior_kilometros), 2) - round($this->warningdivisionbyzero($acumulado_ano_actual_cant_accidentes, $acumulado_ano_actual_kilometros), 2));
    $data[19]->total_ano_anterior = round($this->warningdivisionbyzero($total_ano_anterior_cant_accidentes, $total_ano_anterior_kilometros), 2);
    $data[19]->total_ano_actual = round($this->warningdivisionbyzero($total_ano_actual_cant_accidentes, $total_ano_actual_kilometros), 2);
    $data[19]->total_ano_diferencia = abs(round($this->warningdivisionbyzero($total_ano_anterior_cant_accidentes, $total_ano_anterior_kilometros), 2) - round($this->warningdivisionbyzero($total_ano_actual_cant_accidentes, $total_ano_actual_kilometros), 2));
    //</editor-fold>

    return $data;
  }

// Reporte -> Mostrar el color de vehículo que más choca.
  public function vehiculoconmasaccidentes($request)
  {
    return DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'mtmedio_transportes.id', '=', 'mtgestionaraccidentes.vehiculo_empresa_id')
      ->select('mtmedio_transportes.color as name') // Color del vehiculo
      ->selectRaw("count(vehiculo_empresa_id) as y") // Cant de vehiculos
      ->where('mtmedio_transportes.instalacion_id', $request['instalacion_id'])
      ->groupByRaw('mtmedio_transportes.color')
      ->get();
  }

// Reporte -> Mostar la cantidad de vehículos que se han accidentado por horario
  public function horarioconmasaccidentes()
  {
    $mannana = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'mtmedio_transportes.id', '=', 'mtgestionaraccidentes.vehiculo_empresa_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtmedio_transportes.instalacion_id')
      ->select('mtinstalacion.nombre as instalacion', DB::raw('COUNT(mtgestionaraccidentes.id) as cant_vehiculos'))
      ->whereTime('hora', '>', '06:00:00')
      ->whereTime('hora', '<', '11:59:00')
      ->groupBy('mtinstalacion.nombre')
      ->get();
    $tarde = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'mtmedio_transportes.id', '=', 'mtgestionaraccidentes.vehiculo_empresa_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtmedio_transportes.instalacion_id')
      ->select('mtinstalacion.nombre as instalacion', DB::raw('COUNT(mtgestionaraccidentes.id) as cant_vehiculos'))
      ->whereTime('hora', '>', '12:00:00')
      ->whereTime('hora', '<', '18:59:00')
      ->groupBy('mtinstalacion.nombre')
      ->get();
    $noche = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'mtmedio_transportes.id', '=', 'mtgestionaraccidentes.vehiculo_empresa_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtmedio_transportes.instalacion_id')
      ->select('mtinstalacion.nombre as instalacion', DB::raw('COUNT(mtgestionaraccidentes.id) as cant_vehiculos'))
      ->whereTime('hora', '>', '19:00:00')
      ->whereTime('hora', '<', '23:59:00')
      ->groupBy('mtinstalacion.nombre')
      ->get();
    $madrugada = DB::table('mtgestionaraccidentes')
      ->join('mtmedio_transportes', 'mtmedio_transportes.id', '=', 'mtgestionaraccidentes.vehiculo_empresa_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtmedio_transportes.instalacion_id')
      ->select('mtinstalacion.nombre as instalacion', DB::raw('COUNT(mtgestionaraccidentes.id) as cant_vehiculos'))
      ->whereTime('hora', '>', '00:00:00')
      ->whereTime('hora', '<', '05:59:00')
      ->groupBy('mtinstalacion.nombre')
      ->get();

    $respuesta = [];
    $i = 0;
    $item[$i] = new stdClass();
    $item[$i]->mannana = $mannana;
    $item[$i]->tarde = $tarde;
    $item[$i]->noche = $noche;
    $item[$i]->madrugada = $madrugada;
    array_push($respuesta, $item[$i]);
    return response()->json(['data' => $respuesta]);
  }

  public function dashboard()
  {
    $meses = DB::table('mtgestionaraccidentes')
      ->selectRaw("DATE_FORMAT(fecha,'%c')fecha")
      ->groupByRaw("fecha")
      ->pluck('fecha');

    $km_recorridos = DB::table('mtkm_recorridos')
      ->selectRaw("SUM(km_recorridos) AS km_recorridos")
      ->pluck('km_recorridos');

    $accidentes = DB::table('mtgestionaraccidentes')
      ->selectRaw("DATE_FORMAT(fecha,'%c')fecha")
      ->selectRaw("COUNT(*) + ? AS accidentes", [$km_recorridos])
      ->groupBy('fecha')
      ->pluck('accidentes');

    $datas = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    foreach ($meses as $index => $mes) {
      $datas[$mes] = $accidentes[$index];
    }
    $response = [];
    $item[0] = new stdClass();
    $item[0]->name = 'Índice accidentalidad';
    $item[0]->data = $datas;
    array_push($response, $item[0]);

    return $response;
  }
}
