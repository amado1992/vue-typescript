<?php


namespace App\Repositories;

use App\Models\MTUnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTUnidadMedidaRepository extends BaseRepository
{
  private $mtUnidadMedida;

  public function __construct(MTUnidadMedida $mtUnidadMedida)
  {
    $this->mtUnidadMedida = $mtUnidadMedida;
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
    return MTUnidadMedida::class;
  }

  public function listMTUnidadMedida()
  {
    $mtUnidadMedida = $this->mtUnidadMedida->orderBy('created_at', 'desc')->get();

    if (!isset($mtUnidadMedida) || $mtUnidadMedida == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay unidades de medida que mostrar']);
    return response()->json($mtUnidadMedida);
  }

  public function getUnidadmedidas()
  {
    return DB::table('mtunidadmedida')
      ->orderBy('mtunidadmedida.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTUnidadMedida($request)
  {
    $data = new MTUnidadMedida([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTUnidadMedida($id, Request $request)
  {
    $data = MTUnidadMedida::find($id);
    $data->nombre = $request->input('nombre');
    $data->save();
    return response()->json($data);
  }

  public function eliminarMTUnidadMedida($id)
  {
    MTUnidadMedida::destroy($id);
    return response()->json('Unidad de medida eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
