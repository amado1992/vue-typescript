<?php


namespace App\Repositories;

use App\Models\MTMotivoPMTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTMotivoPMTransporteRepository
{

  private $mtMotivoPMTransporte;

  public function __construct(MTMotivoPMTransporte $mtMotivoPMTransporte)
  {
    $this->mtMotivoPMTransporte = $mtMotivoPMTransporte;
  }

  protected $fieldSearchable = [
    'motivo',
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
    return MTMotivoPMTransporte::class;
  }

  public function listMTMotivoPMTransporte()
  {
    $mtMotivoPMTransporte = $this->mtMotivoPMTransporte->orderBy('created_at', 'desc')->get();

    if (!isset($mtMotivoPMTransporte) || $mtMotivoPMTransporte == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay motivo de paralisis de  medios de transporte que mostrar']);
    return response()->json($mtMotivoPMTransporte);

  }

  public function getMTMotivoPMTransporte()
  {
    return DB::table('mtmotivopmtransportes')
      ->orderBy('mtmotivopmtransportes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTMotivoPMTransporte($request)
  {
    $data = new MTMotivoPMTransporte([
      'motivo' => $request['motivo']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTMotivoPMTransporte($id, Request $request)
  {
    $mtMotivoPMTransporte = MTMotivoPMTransporte::find($id);
    $mtMotivoPMTransporte->update($request->all());
    return response()->json('Motivo de paralisis de medio de transporte modificado');
  }

  public function eliminarMTMotivoPMTransporte($id, Request $request)
  {
    $mtMotivoPMTransporte = MTMotivoPMTransporte::find($id);
    $mtMotivoPMTransporte->delete($request->all());
    return response()->json('Motivo de paralisis de medio de transporte eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
