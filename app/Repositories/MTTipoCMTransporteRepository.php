<?php


namespace App\Repositories;

use App\Models\MTTipoCMTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoCMTransporteRepository
{

  private $mtTipoCMTransporte;

  public function __construct(MTTipoCMTransporte $mtTipoCMTransporte)
  {
    $this->mtTipoCMTransporte = $mtTipoCMTransporte;
  }

  protected $fieldSearchable = [
    'combustible',
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
    return MTTipoCMTransporte::class;
  }

  public function listMTTipoCMTransporte()
  {
    $mtTipoCMTransporte = $this->mtTipoCMTransporte->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoCMTransporte) || $mtTipoCMTransporte == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipos de combustibles en medios de transporte que mostrar']);
    return response()->json($mtTipoCMTransporte);

  }

  public function getMTTipoCMTransporte()
  {
    return DB::table('mttipocmtransportes')
      ->orderBy('mttipocmtransportes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoCMTransporte($request)
  {
    $data = new MTTipoCMTransporte([
      'combustible' => $request['combustible']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoCMTransporte($id, Request $request)
  {
    $mtTipoCMTransporte = MTTipoCMTransporte::find($id);
    $mtTipoCMTransporte->update($request->all());
    return response()->json('Tipo de combustible de medio de transporte modificado');
  }

  public function eliminarMTTipoCMTransporte($id, Request $request)
  {
    $mtTipoCMTransporte = MTTipoCMTransporte::find($id);
    $mtTipoCMTransporte->delete($request->all());
    return response()->json('Tipo de conmbustible de medio de transporte eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
