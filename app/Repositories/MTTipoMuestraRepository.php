<?php


namespace App\Repositories;


use App\Models\MTTipoMuestra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTTipoMuestraRepository
{
  private $mtTipoMuestra;

  public function __construct(MTTipoMuestra $mtTipoMuestra)
  {
    $this->mtTipoMuestra = $mtTipoMuestra;
  }

  protected $fieldSearchable = [
    'codigo', 'tipo_muestra'
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
    return MTTipoMuestra::class;
  }

  public function listMTTipoMuestra()
  {
    $mtTipoMuestra = $this->mtTipoMuestra->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoMuestra) || $mtTipoMuestra == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipo de muestra que mostrar']);
    return response()->json($mtTipoMuestra);
  }

  public function generateRandomString($length) {
    $arreglo = DB::table('mttipo_muestras')->count();
    $last_tipo_muestra = MTTipoMuestra::all()->last();
    if (empty($last_tipo_muestra))
      $cont = 1;
    else
      $cont = $arreglo + 1;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.$cont;
  }

  public function createMTTipoMuestra($request)
  {
    $data = new MTTipoMuestra([
      'codigo' => $this->generateRandomString(3), //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: ABP9, ABC582, GTY556).
      'tipo_muestra' => $request['tipo_muestra']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoMuestra($id, Request $request)
  {
    $data = MTTipoMuestra::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  public function eliminarMTTipoMuestra($id)
  {
    MTTipoMuestra::destroy($id);
    return response()->json('Tipo de muestra eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
