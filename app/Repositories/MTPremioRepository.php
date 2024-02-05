<?php

namespace App\Repositories;

use App\Models\MTOsde;
use App\Models\MTPremio;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTPremioRepository extends BaseRepository
{
  private $mtPremio;

  public function __construct(MTPremio $mtPremio)
  {
    $this->mtPremio = $mtPremio;
  }

  protected $fieldSearchable = [
    'nombre',
    'fecha',
    'categoriapremio_id'
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
    return MTPremio::class;
  }

  public function listMTPremio()
  {
    $mtPremio = $this->mtPremio->with([
      'categoriapremios:id,nombre',
      'instalaciones:id,nombre'
    ])->orderBy('created_at', 'desc')->get();

    if (!isset($mtPremio) || $mtPremio == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay premios que mostrar']);
    return response()->json($mtPremio);
  }

  public function createMTPremio($request, $instalacionId)
  {
    $data = new MTPremio([
      'nombre' => $request['nombre'],
      'fecha' => $request['fecha'],
      'categoriapremio_id' => $request['categoriapremio_id'],
      'instalacion_id' => $instalacionId,
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTPremio($id, Request $request)
  {
    $premio = MTPremio::find($id);
    $premio->nombre = $request->input('nombre');
    $premio->fecha = $request->input('fecha');
    $premio->categoriapremio_id = $request->input('categoriapremio_id');
    $premio->save();
    return response()->json($premio);
  }

  public function eliminarMTPremio($id)
  {
    MTPremio::destroy($id);
    return response()->json('Premio eliminado');
  }

  // Reporte 1 - Mostrar cantidad de premios por OSDE
  public function cantPremiosOsde()
  {
    $cantPremiosOsde = DB::table('mtpremio')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtpremio.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->select(
        'mtosde.nombre',
        DB::raw('COUNT(mtpremio.instalacion_id) as cant_premios')
      )
      ->groupBy('mtinstalacion.osde_id')
      ->get();
    return $cantPremiosOsde;
  }

  // Reporte 2 - Mostrar por OSDE la cantidad de premios por categoría: nacionales e internacionales
  public function cantPremiosCategoriaOsde()
  {
    $nacionales = DB::table('mtpremio')
      ->join('mtcategoriapremio', 'mtcategoriapremio.id', '=', 'mtpremio.categoriapremio_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtpremio.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->select('mtosde.nombre', DB::raw('COUNT(mtpremio.categoriapremio_id) as cant_premios'))
      ->where('categoriapremio_id', '1')
      ->groupBy('instalacion_id');

    $internacionales = DB::table('mtpremio')
      ->join('mtcategoriapremio', 'mtcategoriapremio.id', '=', 'mtpremio.categoriapremio_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'mtpremio.instalacion_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtinstalacion.osde_id')
      ->select('mtosde.nombre', DB::raw('COUNT(mtpremio.categoriapremio_id) as cant_premios'))
      ->where('categoriapremio_id', '2')
      ->groupBy('instalacion_id');

    $respuesta = [];
    $i = 0;
    $item[$i] = new stdClass();
    $item[$i]->premios_nacionales = $nacionales->get();
    $item[$i]->premios_internacionales = $internacionales->get();
    array_push($respuesta, $item[$i]);
    return $respuesta;
  }

  // Reporte 3 - Mostrar el % que representan por OSDE la cantidad de instalaciones premiadas
  public function TotalInstalacionesPremiadas()
  {
    $totalinstalacionespremiadas = DB::table('mtpremio')->count();
    return $totalinstalacionespremiadas;
  }

  private function getCantPremiosXInstalacin($instalacionId)
  {
    return MTPremio::where('instalacion_id', $instalacionId)->count();
  }

  private function getCantPremiosXEntidad($entidadId)
  {
    $cantTotal = 0;
    $osde = MTOsde::where('id', $entidadId)->with('instalaciones')->get()->first();
    $instalaciones = $osde->instalaciones;
    foreach ($instalaciones as $instalacion) {
      $cantTotal = $cantTotal + $this->getCantPremiosXInstalacin($instalacion->id);
    }
    return $cantTotal;
  }

  public function Porciento($identidad)
  {
    $result = 0;
    if ($this->getCantPremiosXEntidad($identidad) != 0 && $this->TotalInstalacionesPremiadas() != 0)
      $result = $this->getCantPremiosXEntidad($identidad) * 100 / $this->TotalInstalacionesPremiadas();
    return $result;
  }

  public function porcientoPremiosEntidad()
  {
    $result = [];
    $osdes = DB::table('mtosde')->get();
    foreach ($osdes as $e) {
      $i = 0;
      $entidad[$i] = new stdClass();
      $entidad[$i]->nombre = $e->nombre;
      $entidad[$i]->porciento = $this->Porciento($e->id);
      array_push($result, $entidad[$i]);
    }
    return $result;
  }
  /** Dashboard */
  public function getCantPremiosXcategoria($request)
  {
    return DB::table('mtpremio')
      ->join('mtcategoriapremio', 'mtcategoriapremio.id', '=', 'mtpremio.categoriapremio_id')
      ->select('mtcategoriapremio.nombre as name') // Nombre premio
      ->selectRaw("count(categoriapremio_id) as y") // Total de premios
      ->where('instalacion_id', $request['instalacion_id'])
      ->groupBy('mtcategoriapremio.nombre')
      ->get();
  }

  public function getCantPremios5years($request)
  {
    return DB::table('mtpremio')
      ->selectRaw("year(fecha) name") //Fecha
      ->selectRaw("count(categoriapremio_id) y") //Total de premios
      ->where('instalacion_id', $request['instalacion_id'])
      ->limit(5)
      ->groupByRaw('year(fecha)')
      ->orderBy('fecha', 'asc')
      ->get();
  }

  /**
   * Configure the Model
   *
   * @return string
   */
}
