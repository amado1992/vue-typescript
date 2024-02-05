<?php


namespace App\Repositories;

use App\Models\MTClasificacionAccidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTClasificacionAccidenteRepository
{

  private $mtClasificacionAccidente;

  public function __construct(MTClasificacionAccidente $mtClasificacionAccidente)
  {
    $this->mtClasificacionAccidente = $mtClasificacionAccidente;
  }

  protected $fieldSearchable = [
    'clasificacion_accidente',
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
    return MTClasificacionAccidente::class;
  }

  public function listMTClasificacionAccidente()
  {
    $mtClasificacionAccidente = $this->mtClasificacionAccidente->orderBy('created_at', 'desc')->get();

    if (!isset($mtClasificacionAccidente) || $mtClasificacionAccidente == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay clasificaciones de accidentes que mostrar']);
    return response()->json($mtClasificacionAccidente);

  }

  public function getMTClasificacionAccidente()
  {
    return DB::table('mtclasificacionaccidentes')
      ->orderBy('mtclasificacionaccidentes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTClasificacionAccidente($request)
  {
    $data = new MTClasificacionAccidente([
      'clasificacion_accidente' => $request['clasificacion_accidente']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTClasificacionAccidente($id, Request $request)
  {
    $mtClasificacionAccidente = MTClasificacionAccidente::find($id);
    $mtClasificacionAccidente->update($request->all());
    return response()->json('Clasificacion de accidente modificado');
  }

  public function eliminarMTClasificacionAccidente($id, Request $request)
  {
    $mtClasificacionAccidente = MTClasificacionAccidente::find($id);
    $mtClasificacionAccidente->delete($request->all());
    return response()->json('Clasificacion de accidente eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
