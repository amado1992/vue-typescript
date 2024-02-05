<?php


namespace App\Repositories;

use App\Models\MTTipoVTuristicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoVTuristicosRepository
{

  private $mtTipoVTuristicos;

  public function __construct(MTTipoVTuristicos $mtTipoVTuristicos)
  {
    $this->mtTipoVTuristicos = $mtTipoVTuristicos;
  }

  protected $fieldSearchable = [
    'tipo',
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
    return MTTipoVTuristicos::class;
  }

  public function listMTTipoVTuristicos()
  {
    $mtTipoVTuristicos = $this->mtTipoVTuristicos->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoVTuristicos) || $mtTipoVTuristicos == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay vehículos turísticos que mostrar']);
    return response()->json($mtTipoVTuristicos);

  }

  public function getMTTipoVTuristicos()
  {
    return DB::table('mttipovturisticos')
      ->orderBy('mttipovturisticos.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoVTuristicos($request)
  {
    $data = new MTTipoVTuristicos([
      'tipo' => $request['tipo']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoVTuristicos($id, Request $request)
  {
    $mtTipoVTuristicos = MTTipoVTuristicos::find($id);
    $mtTipoVTuristicos->update($request->all());
    return response()->json('Tipo de vehiculo turístico modificado');
  }

  public function eliminarMTTipoVTuristicos($id, Request $request)
  {
    $mtTipoVTuristicos = MTTipoVTuristicos::find($id);
    $mtTipoVTuristicos->delete($request->all());
    return response()->json('Tipo de vehículo turístico eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
