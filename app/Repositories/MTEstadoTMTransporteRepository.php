<?php


namespace App\Repositories;

use App\Models\MTEstadoTMTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTEstadoTMTransporteRepository
{

  private $mtEstadoTMTransporte;

  public function __construct(MTEstadoTMTransporte $mtEstadoTMTransporte)
  {
    $this->mtEstadoTMTransporte = $mtEstadoTMTransporte;
  }

  protected $fieldSearchable = [
    'estado',
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
    return MTEstadoTMTransporte::class;
  }

  public function listMTEstadoTMTransporte()
  {
    $mtEstadoTMTransporte = $this->mtEstadoTMTransporte->orderBy('created_at', 'desc')->get();

    if (!isset($mtEstadoTMTransporte) || $mtEstadoTMTransporte == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay estados tecnicos y medios de transporte que mostrar']);
    return response()->json($mtEstadoTMTransporte);

  }

  public function getMTEstadoTMTransportes()
  {
    return DB::table('mtestadotmtransportes')
      ->orderBy('mtestadotmtransportes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTEstadoTMTransporte($request)
  {
    $data = new MTEstadoTMTransporte([
      'estado' => $request['estado']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTEstadoTMTransporte($id, Request $request)
  {
    $mtEstadoTMTransporte = MTEstadoTMTransporte::find($id);
    $mtEstadoTMTransporte->update($request->all());
    return response()->json('Estado tecnico de medio de transporte modificado');
  }

  public function eliminarMTEstadoTMTransporte($id, Request $request)
  {
    $mtEstadoTMTransporte = MTEstadoTMTransporte::find($id);
    $mtEstadoTMTransporte->delete($request->all());
    return response()->json('Estado tecnico de medio de transporte eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
