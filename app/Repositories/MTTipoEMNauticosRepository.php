<?php


namespace App\Repositories;

use App\Models\MTTipoEMNauticos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoEMNauticosRepository
{

  private $mtTipoEMNauticos;

  public function __construct(MTTipoEMNauticos $mtTipoEMNauticos)
  {
    $this->mtTipoEMNauticos = $mtTipoEMNauticos;
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
    return MTTipoEMNauticos::class;
  }

  public function listMTTipoEMNauticos()
  {
    $mtTipoEMNauticos = $this->mtTipoEMNauticos->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoEMNauticos) || $mtTipoEMNauticos == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipos de embarcaciones y servicios náuticos que mostrar']);
    return response()->json($mtTipoEMNauticos);

  }

  public function getMTTipoEMNauticos()
  {
    return DB::table('mttipoemnauticos')
      ->orderBy('mttipoemnauticos.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoEMNauticos($request)
  {
    $data = new MTTipoEMNauticos([
      'tipo' => $request['tipo']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoEMNauticos($id, Request $request)
  {
    $mtTipoEMNauticos = MTTipoEMNauticos::find($id);
    $mtTipoEMNauticos->update($request->all());
    return response()->json('Tipo de embarcación y medio náutico modificado');
  }

  public function eliminarMTTipoEMNauticos($id, Request $request)
  {
    $mtTipoEMNauticos = MTTipoEMNauticos::find($id);
    $mtTipoEMNauticos->delete($request->all());
    return response()->json('Tipo de embarcación y medio náutico eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
