<?php


namespace App\Repositories;


use App\Models\MTTipoFoco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTTipoFocoRepository
{
  private $mtTipoFoco;

  public function __construct(MTTipoFoco $mtTipoFoco)
  {
    $this->mtTipoFoco = $mtTipoFoco;
  }

  protected $fieldSearchable = [
    'codigo', 'tipo_foco'
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
    return MTTipoFoco::class;
  }

  public function listMTTipoFoco()
  {
    $mtTipoFoco = $this->mtTipoFoco->orderBy('created_at', 'desc')->get();

    if (!isset($mtTipoFoco) || $mtTipoFoco == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipo de foco que mostrar']);
    return response()->json($mtTipoFoco);
  }

  public function generateRandomString($length) {
    $arreglo = DB::table('mttipo_focos')->count();
    $last_tipo_foco = MTTipoFoco::all()->last();
    if (empty($last_tipo_foco))
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

  public function createMTTipoFoco($request)
  {
    $data = new MTTipoFoco([
      'codigo' => $this->generateRandomString(3), //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: ABC9, ABC652, GTY56).
      'tipo_foco' => $request['tipo_foco']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTipoFoco($id, Request $request)
  {
    $data = MTTipoFoco::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  public function eliminarMTTipoFoco($id)
  {
    MTTipoFoco::destroy($id);
    return response()->json('Tipo de foco eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
