<?php


namespace App\Repositories;

use App\Models\MTTipoAlmacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoAlmacenRepository
{

  private $mtTipoAlmacen;

  public function __construct(MTTipoAlmacen $mtTipoAlmacen)
  {
    $this->mtTipoAlmacen = $mtTipoAlmacen;
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
    return MTTipoAlmacen::class;
  }

  public function listMTTipoAlmacen()
  {
    $mtTipoAlmacen = $this->mtTipoAlmacen->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoAlmacen) || $mtTipoAlmacen == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipo de almacen que mostrar']);
    return response()->json($mtTipoAlmacen);
  }

  public function getMTTipo_almacenes()
  {
    return DB::table('mttipoalmacen')
      ->orderBy('mttipoalmacen.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoAlmacen($request)
  {
    $data = new MTTipoAlmacen([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoAlmacen($id, Request $request)
  {
    $mtTipoAlmacen = MTTipoAlmacen::find($id);
    $mtTipoAlmacen->update($request->all());
    return response()->json('Tipo de Almacen modificado');
  }

  public function eliminarMTTipoAlmacen($id, Request $request)
  {
    $mtTipoAlmacen = MTTipoAlmacen::find($id);
    $mtTipoAlmacen->delete($request->all());
    return response()->json('Tipo de Almacen eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
