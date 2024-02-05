<?php


namespace App\Repositories;

use App\Models\MTTipoFlota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTipoFlotaRepository
{

  private $mtTipoFlota;

  public function __construct(MTTipoFlota $mtTipoFlota)
  {
    $this->mtTipoFlota = $mtTipoFlota;
  }

  protected $fieldSearchable = [
    'tipo_flota',
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
    return MTTipoFlota::class;
  }

  public function listMTTipoFlota()
  {
    $mtTipoFlota = $this->mtTipoFlota->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoFlota) || $mtTipoFlota == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay categorias que mostrar']);
    return response()->json($mtTipoFlota);

  }

  public function getMTTipoFlotas()
  {
    return DB::table('mttipoflotas')
      ->orderBy('mttipoflotas.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTipoFlota($request)
  {
    $data = new MTTipoFlota([
      'tipo_flota' => $request['tipo_flota']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoFlota($id, Request $request)
  {
    $mtTipoFlota = MTTipoFlota::find($id);
    $mtTipoFlota->update($request->all());
    return response()->json('Tipo de flota modificada');
  }

  public function eliminarMTTipoFlota($id, Request $request)
  {
    $mtTipoFlota = MTTipoFlota::find($id);
    $mtTipoFlota->delete($request->all());
    return response()->json('Tipo de flota eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
