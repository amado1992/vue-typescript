<?php


namespace App\Repositories;

use App\Models\MTVictimaAccidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTVictimaAccidenteRepository
{

  private $mtVictimaAccidente;

  public function __construct(MTVictimaAccidente $mtVictimaAccidente)
  {
    $this->mtVictimaAccidente = $mtVictimaAccidente;
  }

  protected $fieldSearchable = [
    'victima_accidente',
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
    return MTVictimaAccidente::class;
  }

  public function listMTVictimaAccidente()
  {
    $mtVictimaAccidente = $this->mtVictimaAccidente->orderBy('created_at', 'desc')->get();

    if (!isset($mtVictimaAccidente) || $mtVictimaAccidente == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay victimas de accidentes que mostrar']);
    return response()->json($mtVictimaAccidente);

  }

  public function getMTVictimaAccidente()
  {
    return DB::table('mtvictimaaccidentes')
      ->orderBy('mtvictimaaccidentes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTVictimaAccidente($request)
  {
    $data = new MTVictimaAccidente([
      'victima_accidente' => $request['victima_accidente']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTVictimaAccidente($id, Request $request)
  {
    $mtVictimaAccidente = MTVictimaAccidente::find($id);
    $mtVictimaAccidente->update($request->all());
    return response()->json('Victima de accidente modificado');
  }

  public function eliminarMTVictimaAccidente($id, Request $request)
  {
    $mtVictimaAccidente = MTVictimaAccidente::find($id);
    $mtVictimaAccidente->delete($request->all());
    return response()->json('Victima de accidente eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
