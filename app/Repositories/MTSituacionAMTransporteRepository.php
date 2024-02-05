<?php


namespace App\Repositories;

use App\Models\MTSituacionAMTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTSituacionAMTransporteRepository
{

  private $mtSituacionAMTransporte;

  public function __construct(MTSituacionAMTransporte $mtSituacionAMTransporte)
  {
    $this->mtSituacionAMTransporte = $mtSituacionAMTransporte;
  }

  protected $fieldSearchable = [
    'situacion_actual',
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
    return MTSituacionAMTransporte::class;
  }

  public function listMTSituacionAMTransporte()
  {
    $mtSituacionAMTransporte = $this->mtSituacionAMTransporte->orderBy('created_at', 'desc')->get();

    if (!isset($mtSituacionAMTransporte) || $mtSituacionAMTransporte == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay situacion actual en medios de transporte que mostrar']);
    return response()->json($mtSituacionAMTransporte);

  }

  public function getMTSituacionAMTransporte()
  {
    return DB::table('mtsituacionamtransportes')
      ->orderBy('mtsituacionamtransportes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTSituacionAMTransporte($request)
  {
    $data = new MTSituacionAMTransporte([
      'situacion_actual' => $request['situacion_actual']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTSituacionAMTransporte($id, Request $request)
  {
    $mtSituacionAMTransporte = MTSituacionAMTransporte::find($id);
    $mtSituacionAMTransporte->update($request->all());
    return response()->json('Situacion actual de medio de transporte modificado');
  }

  public function eliminarMTSituacionAMTransporte($id, Request $request)
  {
    $mtSituacionAMTransporte = MTSituacionAMTransporte::find($id);
    $mtSituacionAMTransporte->delete($request->all());
    return response()->json('Situacion actual de medio de transporte eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
