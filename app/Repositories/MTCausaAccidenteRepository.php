<?php


namespace App\Repositories;

use App\Models\MTCausaAccidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTCausaAccidenteRepository
{

  private $mtCausaAccidente;

  public function __construct(MTCausaAccidente $mtCausaAccidente)
  {
    $this->mtCausaAccidente = $mtCausaAccidente;
  }

  protected $fieldSearchable = [
    'causa_accidente',
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
    return MTCausaAccidente::class;
  }

  public function listMTCausaAccidente()
  {
    $mtCausaAccidente = $this->mtCausaAccidente->orderBy('created_at', 'desc')->get();

    if (!isset($mtCausaAccidente) || $mtCausaAccidente == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay causas de accidentes que mostrar']);
    return response()->json($mtCausaAccidente);

  }

  public function getMTCausaAccidente()
  {
    return DB::table('mtcausaaccidentes')
      ->orderBy('mtcausaaccidentes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTCausaAccidente($request)
  {
    $data = new MTCausaAccidente([
      'causa_accidente' => $request['causa_accidente']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTCausaAccidente($id, Request $request)
  {
    $mtCausaAccidente = MTCausaAccidente::find($id);
    $mtCausaAccidente->update($request->all());
    return response()->json('Causa de accidente modificado');
  }

  public function eliminarMTCausaAccidente($id, Request $request)
  {
    $mtCausaAccidente = MTCausaAccidente::find($id);
    $mtCausaAccidente->delete($request->all());
    return response()->json('Causa de accidente eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
