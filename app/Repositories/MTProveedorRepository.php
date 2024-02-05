<?php


namespace App\Repositories;

use App\Models\MTProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTProveedorRepository extends BaseRepository
{
  private $mtProveedor;

  public function __construct(MTProveedor $mtProveedor)
  {
    $this->mtProveedor = $mtProveedor;
  }

  protected $fieldSearchable = [
    'codigo',
    'nombre',
    'municipio',
    'tipo_proveedor_id'
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
    return MTProveedor::class;
  }

  public function listMTProveedor()
  {
    $mtProveedor = $this->mtProveedor->with([
      'proveedores:id,nombre',
      'municipios:id,nombre'
    ])->orderBy('created_at', 'desc')->get();

    if (!isset($mtProveedor) || $mtProveedor == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay proveedores que mostrar']);
    return response()->json($mtProveedor);
  }

  public function getProveedores()
  {
    return DB::table('mtproveedor')
      ->orderBy('mtproveedor.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTProveedor($request)
  {
    $data = new MTProveedor([
      'codigo' => $request['codigo'],
      'nombre' => $request['nombre'],
      'municipio_id' => $request['municipio_id'],
      'tipo_proveedor_id' => $request['tipo_proveedor_id']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTProveedor($id, Request $request)
  {
    $data = MTProveedor::find($id);
    $data->codigo = $request->input('codigo');
    $data->nombre = $request->input('nombre');
    $data->municipio_id = $request->input('municipio_id');
    $data->tipo_proveedor_id = $request->input('tipo_proveedor_id');
    $data->save();
    return response()->json($data);
  }

  public function eliminarMTProveedor($id)
  {
    MTProveedor::destroy($id);
    return response()->json('Proveedor eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */
}
