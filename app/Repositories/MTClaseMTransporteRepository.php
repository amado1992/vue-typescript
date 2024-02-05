<?php


namespace App\Repositories;

use App\Models\MTClaseMTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTClaseMTransporteRepository
{

  private $mtClaseMTransporte;

  public function __construct(MTClaseMTransporte $mtClaseMTransporte)
  {
    $this->mtClaseMTransporte = $mtClaseMTransporte;
  }

  protected $fieldSearchable = [
    'clase',
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
    return MTClaseMTransporte::class;
  }

  public function listMTClaseMTransporte()
  {
    $mtClaseMTransporte = $this->mtClaseMTransporte->orderBy('created_at', 'desc')->get();

    if (!isset($mtClaseMTransporte) || $mtClaseMTransporte == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay clase de  medios de transporte que mostrar']);
    return response()->json($mtClaseMTransporte);

  }

  public function getMTClaseMTransporte()
  {
    return DB::table('mtclasemtransportes')
      ->orderBy('mtclasemtransportes.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTClaseMTransporte($request)
  {
    $data = new MTClaseMTransporte([
      'clase' => $request['clase']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTClaseMTransporte($id, Request $request)
  {
    $mtClaseMTransporte = MTClaseMTransporte::find($id);
    $mtClaseMTransporte->update($request->all());
    return response()->json('Clase de medio de transporte modificado');
  }

  public function eliminarMTClaseMTransporte($id, Request $request)
  {
    $mtClaseMTransporte = MTClaseMTransporte::find($id);
    $mtClaseMTransporte->delete($request->all());
    return response()->json('Clase de medio de transporte eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
