<?php


namespace App\Repositories;

use App\Models\MTTipoVEspecializados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoVEspecializadosRepository
{

  private $mtTipoVEspecializados;

  public function __construct(MTTipoVEspecializados $mtTipoVEspecializados)
  {
    $this->mtTipoVEspecializados = $mtTipoVEspecializados;
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
    return MTTipoVEspecializados::class;
  }

  public function listMTTipoVEspecializados()
  {
    $mtTipoVEspecializados = $this->mtTipoVEspecializados->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoVEspecializados) || $mtTipoVEspecializados == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay vehículos especializados que mostrar']);
    return response()->json($mtTipoVEspecializados);

  }

  public function getMTTipoVEspecializados()
  {
    return DB::table('mttipovespecializados')
      ->orderBy('mttipovespecializados.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoVEspecializados($request)
  {
    $data = new MTTipoVEspecializados([
      'tipo' => $request['tipo']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoVEspecializados($id, Request $request)
  {
    $mtTipoVEspecializados = MTTipoVEspecializados::find($id);
    $mtTipoVEspecializados->update($request->all());
    return response()->json('Tipo de vehiculo administrativo y de servicio modificado');
  }

  public function eliminarMTTipoVEspecializados($id, Request $request)
  {
    $mtTipoVEspecializados = MTTipoVEspecializados::find($id);
    $mtTipoVEspecializados->delete($request->all());
    return response()->json('Tipo de vehículo especilizado eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
