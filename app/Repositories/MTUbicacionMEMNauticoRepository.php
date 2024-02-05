<?php


namespace App\Repositories;

use App\Models\MTUbicacionMEMNautico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTUbicacionMEMNauticoRepository
{

  private $mtUbicacionMEMNautico;

  public function __construct(MTUbicacionMEMNautico $mtUbicacionMEMNautico)
  {
    $this->mtUbicacionMEMNautico = $mtUbicacionMEMNautico;
  }

  protected $fieldSearchable = [
    'ubicacion',
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
    return MTUbicacionMEMNautico::class;
  }

  public function listMTUbicacionMEMNautico()
  {
    $mtUbicacionMEMNautico = $this->mtUbicacionMEMNautico->orderBy('created_at', 'desc')->get();

    if (!isset($mtUbicacionMEMNautico) || $mtUbicacionMEMNautico == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay ubicacion del motor de embarcaciones y servicios náuticos que mostrar']);
    return response()->json($mtUbicacionMEMNautico);

  }

  public function getMTUbicacionMEMNautico()
  {
    return DB::table('mtubicacionmemnauticos')
      ->orderBy('mtubicacionmemnauticos.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTUbicacionMEMNautico($request)
  {
    $data = new MTUbicacionMEMNautico([
      'ubicacion' => $request['ubicacion']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTUbicacionMEMNautico($id, Request $request)
  {
    $mtUbicacionMEMNautico = MTUbicacionMEMNautico::find($id);
    $mtUbicacionMEMNautico->update($request->all());
    return response()->json('Ubicacion de motor de embarcación y medio náutico modificado');
  }

  public function eliminarMTUbicacionMEMNautico($id, Request $request)
  {
    $mtUbicacionMEMNautico = MTUbicacionMEMNautico::find($id);
    $mtUbicacionMEMNautico->delete($request->all());
    return response()->json('Ubicacion del motor de embarcación y medio náutico eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
