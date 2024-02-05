<?php


namespace App\Repositories;

use App\Models\MTActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTActividadRepository
{
  private $mtActividad;
  public function __construct(MTActividad $mtActividad)
  {
    $this->mtActividad = $mtActividad;
  }

  protected $fieldSearchable = [
    'nombre',
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
    return MTActividad::class;
  }

  public function listMTActividad()
  {
    $mtActividad = $this->mtActividad->orderBy('created_at', 'desc')->get();
    if (!isset($mtActividad) || $mtActividad == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay actividades que mostrar']);
    return response()->json($mtActividad);
  }

  public function getMTActividades()
  {
    return DB::table('mtactividad')
      ->orderBy('mtactividad.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTActividad($request)
  {
    $data = new MTActividad([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTActividad($id, Request $request)
  {
    $mtActividad = MTActividad::find($id);
    $mtActividad->update($request->all());
    return response()->json('Actividad modificada');
  }

  public function eliminarMTActividad($id, Request $request)
  {
    $mtActividad = MTActividad::find($id);
    $mtActividad->delete($request->all());
    return response()->json('Actividad  eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
