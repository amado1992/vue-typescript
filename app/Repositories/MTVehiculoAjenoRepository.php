<?php


namespace App\Repositories;

use App\Models\MTVehiculoAjeno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTVehiculoAjenoRepository
{

  private $mtVehiculoAjeno;

  public function __construct(MTVehiculoAjeno $mtVehiculoAjeno)
  {
    $this->mtVehiculoAjeno = $mtVehiculoAjeno;
  }

  protected $fieldSearchable = [
    'tipo',
    'marca',
    'matricula',
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
    return MTVehiculoAjeno::class;
  }

  public function listMTVehiculoAjeno()
  {
    $mtVehiculoAjeno = $this->mtVehiculoAjeno->orderBy('created_at', 'desc')->get();

    if (!isset($mtVehiculoAjeno) || $mtVehiculoAjeno == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay vehiculos ajenos que mostrar']);
    return response()->json($mtVehiculoAjeno);

  }

  public function getMTVehiculoAjeno()
  {
    return DB::table('mtvehiculoajeno')
      ->orderBy('mtvehiculoajeno.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTVehiculoAjeno($request)
  {
    $data = new MTVehiculoAjeno([
      'tipo' => $request['tipo'],
      'marca' => $request['marca'],
      'matricula' => $request['matricula']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTVehiculoAjeno($id, Request $request)
  {
    $mtVehiculoAjeno = MTVehiculoAjeno::find($id);
    $mtVehiculoAjeno->update($request->all());
    return response()->json('Vehiculo ajeno modificado');
  }

  public function eliminarMTVehiculoAjeno($id, Request $request)
  {
    $mtVehiculoAjeno = MTVehiculoAjeno::find($id);
    $mtVehiculoAjeno->delete($request->all());
    return response()->json('Vehiculo ajeno eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
