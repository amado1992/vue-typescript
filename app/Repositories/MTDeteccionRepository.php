<?php


namespace App\Repositories;


use App\Models\MTDeteccion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTDeteccionRepository
{
  private $mtDeteccion;

  public function __construct(MTDeteccion $mtDeteccion)
  {
    $this->mtDeteccion = $mtDeteccion;
  }

  protected $fieldSearchable = [
    'codigo', 'deteccion'
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
    return MTDeteccion::class;
  }

  public function listMTDeteccion()
  {
    $mtDeteccion = $this->mtDeteccion->orderBy('created_at', 'desc')->get();

    if (!isset($mtDeteccion) || $mtDeteccion == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay deteccion que mostrar']);
    return response()->json($mtDeteccion);
  }

  public function Nocodigo()
  {
    $letra = 'D';
    $arreglo = DB::table('mtdeteccion')->count();
    $last_deteccion = MTDeteccion::all()->last();
    if (empty($last_deteccion))
      $cont = 1;
    else
      $cont = $arreglo + 1;
    return $letra.$cont;
  }

  public function createMTDeteccion($request)
  {
    $data = new MTDeteccion([
      'codigo' => $this->Nocodigo(), //El código sería una letra D y un número consecutivo, ejemplo D1, D2.
      'deteccion' => $request['deteccion']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTDeteccion($id, Request $request)
  {
    $data = MTDeteccion::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  public function eliminarMTDeteccion($id)
  {
    MTDeteccion::destroy($id);
    return response()->json('Deteccion eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
