<?php


namespace App\Repositories;

use App\Models\MTTipoProveedor;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTTipoProveedorRepository extends BaseRepository
{
  private $mtTipoProveedor;

  public function __construct(MTTipoProveedor $mtTipoProveedor)
  {
    $this->mtTipoProveedor = $mtTipoProveedor;
  }

  protected $fieldSearchable = [
    'nombre'
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
    return MTTipoProveedor::class;
  }

  public function listMTTipoProveedor()
  {
    $mtTipoProveedor = $this->mtTipoProveedor->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoProveedor) || $mtTipoProveedor == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipos de proveedores que mostrar']);
    return response()->json($mtTipoProveedor);
  }

  public function getTipo_proveedores()
  {
    return DB::table('mttipoproveedor')
      ->orderBy('mttipoproveedor.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoProveedor($request)
  {
    $data = new MTTipoProveedor([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoProveedor($id, Request $request)
  {
    $data = MTTipoProveedor::find($id);
    $data->nombre = $request->input('nombre');
    $data->save();
    return response()->json($data);
  }

  public function eliminarMTTipoProveedor($id)
  {
    MTTipoProveedor::destroy($id);
    return response()->json('Tipo de proveedor eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
