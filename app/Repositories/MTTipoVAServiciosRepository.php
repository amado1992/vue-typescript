<?php


namespace App\Repositories;

use App\Models\MTTipoVAServicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoVAServiciosRepository
{

  private $mtTipoVAServicios;

  public function __construct(MTTipoVAServicios $mtTipoVAServicios)
  {
    $this->mtTipoVAServicios = $mtTipoVAServicios;
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
    return MTTipoVAServicios::class;
  }

  public function listMTTipoVAServicios()
  {
    $mtTipoVAServicios = $this->mtTipoVAServicios->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoVAServicios) || $mtTipoVAServicios == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay vehículos administrativos y de servicios que mostrar']);
    return response()->json($mtTipoVAServicios);

  }

  public function getMTTipoVAServicios()
  {
    return DB::table('mttipovaservicios')
      ->orderBy('mttipovaservicios.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoVAServicios($request)
  {
    $data = new MTTipoVAServicios([
      'tipo' => $request['tipo']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoVAServicios($id, Request $request)
  {
    $mtTipoFlota = MTTipoVAServicios::find($id);
    $mtTipoFlota->update($request->all());
    return response()->json('Tipo de vehiculo administrativo y de servicio modificado');
  }

  public function eliminarMTTipoVAServicios($id, Request $request)
  {
    $mtTipoFlota = MTTipoVAServicios::find($id);
    $mtTipoFlota->delete($request->all());
    return response()->json('Tipo de vehículo administrativo y de servicio eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
