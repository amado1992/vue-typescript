<?php


namespace App\Repositories;

use App\Models\MTAlmacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTAlmacenRepository
{
  private $mtAlmacen;

  public function __construct(MTAlmacen $mtAlmacen)
  {
    $this->mtAlmacen = $mtAlmacen;
  }

  protected $fieldSearchable = [
    'nombre',
    'codigo',
    'direccion',
    'latitud',
    'longitud',
    'largo',
    'ancho',
    'puntal_libre',
    'altura_prom_estiba',
    'area_util',
    'volumen_util',
    'cant_montacargas',
    'cant_transpaletas',
    'cant_esteras',
    'cant_basculas',
    'cant_estantes',
    'cant_paletas',
    'cant_cajas_paletas',
    'estado_constructivo',
    'estado_estructura',
    'estado_paredes',
    'estado_piso',
    'estado_puertas',
    'estado_ventanas',
    'estado_techo',
    'ventilacion_natural',
    'ventilacion_artificial',
    'iluminacion_natural',
    'iluminacion_artificial',
    'prot_contra_intrusos',
    'prot_contra_incendios',
    'prot_observaciones',
    'plan_inversiones',
    'instalacion_id',
    'categoria_id',
    'distribucion_id',
    'tipo_almacen_id',
    'actividad_id',
    'estado_const_id',
    'temperatura_id'
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
    return MTAlmacen::class;
  }

  public function listMTAlmacen()
  {

    $mtAlmacen = $this->mtAlmacen->with([
      'instalaciones:id,nombre', 'categorias:id,nombre', 'distribuciones:id,nombre',
      'tipoAlmacenes:id,nombre', 'actividades:id,nombre', 'temperaturas:id,nombre'
    ])->orderBy('created_at', 'desc')->get();

    if (!isset($mtAlmacen) || $mtAlmacen == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay almacenes que mostrar']);
    return response()->json($mtAlmacen);
  }

  public function createMTAlmacen($request, $instalacionId)
  {
    $data = new MTAlmacen([
      'nombre' => $request['nombre'],
      'codigo' => $request['codigo'],
      'direccion' => $request['direccion'],
      'latitud' => $request['latitud'],
      'longitud' => $request['longitud'],
      'largo' => $request['largo'],
      'ancho' => $request['ancho'],
      'puntal_libre' => $request['puntal_libre'],
      'altura_prom_estiba' => $request['altura_prom_estiba'],
      'area_util' =>  2 * $request['largo'] * $request['ancho'] + 2 * $request['largo'] * $request['altura_prom_estiba'] + 2 * $request['altura_prom_estiba'] * $request['ancho'],
      'volumen_util' => $request['largo'] * $request['ancho'] * $request['altura_prom_estiba'],
      'cant_montacargas' => $request['cant_montacargas'],
      'cant_transpaletas' => $request['cant_transpaletas'],
      'cant_esteras' => $request['cant_esteras'],
      'cant_basculas' => $request['cant_basculas'],
      'cant_estantes' => $request['cant_estantes'],
      'cant_paletas' => $request['cant_paletas'],
      'cant_cajas_paletas' => $request['cant_cajas_paletas'],
      'estado_constructivo' => $request['estado_constructivo'],
      'estado_estructura' => $request['estado_estructura'],
      'estado_paredes' => $request['estado_paredes'],
      'estado_piso' => $request['estado_piso'],
      'estado_puertas' => $request['estado_puertas'],
      'estado_ventanas' => $request['estado_ventanas'],
      'estado_techo' => $request['estado_techo'],
      'ventilacion_natural' => $request['ventilacion_natural'],
      'ventilacion_artificial' => $request['ventilacion_artificial'],
      'iluminacion_natural' => $request['iluminacion_natural'],
      'iluminacion_artificial' => $request['iluminacion_artificial'],
      'prot_contra_intrusos' => $request['prot_contra_intrusos'],
      'prot_contra_incendios' => $request['prot_contra_incendios'],
      'prot_observaciones' => $request['prot_observaciones'],
      'plan_inversiones' => $request['plan_inversiones'],
      'instalacion_id' => $instalacionId,
      'categoria_id' => $request['categoria_id'],
      'distribucion_id' => $request['distribucion_id'],
      'tipo_almacen_id' => $request['tipo_almacen_id'],
      'actividad_id' => $request['actividad_id'],
      'temperatura_id' => $request['temperatura_id']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function getAlmacenes()
  {
    return DB::table('mtalmacen')
      ->orderBy('mtalmacen.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function updateMTAlmacen($id, Request $request)
  {
    $mtAlmacen = MTAlmacen::find($id);
    $mtAlmacen->update($request->all());
    return response()->json('Almacen modificado');
  }

  public function eliminarMTAlmacen($id, Request $request)
  {
    $mtAlmacen = MTAlmacen::find($id);
    $mtAlmacen->delete($request->all());
    return response()->json('Almacen  eliminado');
  }

  // Reporte -> Mostrar ubicación geográfica de los almacenes por provincia (Mapificación)
  public function mostrar_ubicaciongeografica()
  {
    $ubicacion = DB::table('mtalmacen')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtalmacen.instalacion_id')
      ->join('mtprovincia', 'mtprovincia.id', '=', 'mtinstalacion.provincia_id')
      ->select(
        'mtalmacen.nombre as almacen',
        'mtprovincia.nombre as provincia',
        'mtalmacen.latitud as latitud',
        'mtalmacen.longitud as longitud'
      )
      ->groupBy('mtalmacen.id')
      ->get();
    return response()->json(['data' => $ubicacion]);
  }

  // Reporte ->	Mostrar por OSDE la cantidad de almacenes por cada nivel de categorización
  public function mostrar_almacenescategoria()
  {
    $categorias = DB::table('mtalmacen')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtalmacen.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->join('mtcategoria', 'mtcategoria.id', '=', 'mtalmacen.categoria_id')
      ->select('mtosde.nombre as osde')
      //total de categorias de nivel uno
      ->selectRaw("count(case when mtalmacen.categoria_id = mtcategoria.id and (mtcategoria.id = '1') then 1 end) as categoria_nivel_uno")
      //total de categorias de nivel dos
      ->selectRaw("count(case when mtalmacen.categoria_id = mtcategoria.id and (mtcategoria.id = '2') then 1 end) as categoria_nivel_dos")
      //total de categorias de nivel tres
      ->selectRaw("count(case when mtalmacen.categoria_id = mtcategoria.id and (mtcategoria.id = '3') then 1 end) as categoria_nivel_tres")
      ->groupBy('mtosde.id')
      ->get();
    return response()->json(['data' => $categorias]);
  }

  // Reporte -> Mostrar cantidad de encargados de almacén que no estén capacitados y el % que representan
  public function mostrar_encargadoscapacitados()
  {
    $encargados = DB::table('mtencargado')
      ->join('mtalmacen', 'mtalmacen.id', '=', 'mtencargado.almacen_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtalmacen.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->select('mtosde.nombre as osde')
      //total de encargados de almacenes
      ->selectRaw("count(case when mtalmacen.id = mtencargado.almacen_id then 1 end) as total_encargados")
      //total de encargados de almacenes no capacitados
      ->selectRaw("count(case when mtalmacen.id = mtencargado.almacen_id and (mtencargado.capacitacion = 'No') then 1 end) as encaragados_no_capacitados")
      //calculo % de encargados de almacenes no capacitados
      ->selectRaw("round( 100 * count(case when mtalmacen.id = mtencargado.almacen_id and (mtencargado.capacitacion = 'No') then 1 end) / count(case when (mtalmacen.id = mtencargado.almacen_id) then 1 end)) as porciento")
      ->groupBy('mtosde.id')
      ->get();
    return response()->json(['data' => $encargados]);
  }

  // Reporte -> Mostrar cantidad de almacenes que no tengan inversión y mantenimiento constructivo y su %
  public function mostrar_almacenesinversionmantenimiento()
  {
    $mantenimiento = DB::table('mtalmacen')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtalmacen.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->select('mtosde.nombre as osde')
      //total de almacenes
      ->selectRaw("count(case when mtinstalacion.id = mtalmacen.instalacion_id then 1 end) as total_almacenes")
      //total de encargados de almacenes no capacitados
      ->selectRaw("count(case when mtinstalacion.id = mtalmacen.instalacion_id and (mtalmacen.plan_inversiones = 'No') then 1 end) as almacenes_sin_inversiones")
      //calculo % de encargados de almacenes no capacitados
      ->selectRaw("round( 100 * count(case when mtinstalacion.id = mtalmacen.instalacion_id and (mtalmacen.plan_inversiones = 'No') then 1 end) / count(case when (mtinstalacion.id = mtalmacen.instalacion_id) then 1 end)) as porciento")
      ->groupBy('mtosde.id')
      ->get();
    return response()->json(['data' => $mantenimiento]);
  }

  // Reporte ->	Mostrar cantidad de almacenes con estado constructivo mal y el % que representan
  public function mostrar_almacenesestadoconstructivo()
  {
    $estado = DB::table('mtalmacen')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtalmacen.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->select('mtosde.nombre as osde')
      //total de almacenes
      ->selectRaw("count(case when mtinstalacion.id = mtalmacen.instalacion_id then 1 end) as total_almacenes")
      //total de almacenes con mal estado constructivo
      ->selectRaw("count(case when mtinstalacion.id = mtalmacen.instalacion_id and (mtalmacen.estado_constructivo = 'Malo') then 1 end) as almacenes_mal_estado_constructivo")
      //calculo % de encargados de almacenes no capacitados
      ->selectRaw("round( 100 * count(case when mtinstalacion.id = mtalmacen.instalacion_id and (mtalmacen.estado_constructivo = 'Malo') then 1 end) / count(case when (mtinstalacion.id = mtalmacen.instalacion_id) then 1 end)) as porciento")
      ->groupBy('mtosde.id')
      ->get();
    return response()->json(['data' => $estado]);
  }

  //Dashboard
  public function getAlmacenesXcategoria($request)
  {
    return DB::table('mtalmacen')
      ->join('mtcategoria', 'mtcategoria.id', '=', 'mtalmacen.categoria_id')
      ->select('mtalmacen.nombre')
      //total de categorias de nivel uno
      ->selectRaw("count(case when mtalmacen.categoria_id = mtcategoria.id and (mtcategoria.id = '1') then 1 end) as categoria_nivel_uno")
      //total de categorias de nivel dos
      ->selectRaw("count(case when mtalmacen.categoria_id = mtcategoria.id and (mtcategoria.id = '2') then 1 end) as categoria_nivel_dos")
      //total de categorias de nivel tres
      ->selectRaw("count(case when mtalmacen.categoria_id = mtcategoria.id and (mtcategoria.id = '3') then 1 end) as categoria_nivel_tres")
      ->where('instalacion_id', $request['instalacion_id'])
      ->groupBy('mtalmacen.nombre')
      ->get();
  }
}
