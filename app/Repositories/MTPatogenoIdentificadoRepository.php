<?php


namespace App\Repositories;


use App\Models\MTPatogenoIdentificado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTPatogenoIdentificadoRepository
{
  private $mtPatogenoIdentificado;

  public function __construct(MTPatogenoIdentificado $mtPatogenoIdentificado)
  {
    $this->mtPatogenoIdentificado = $mtPatogenoIdentificado;
  }

  protected $fieldSearchable = [
    'codigo', 'patogeno_identificado'
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
    return MTPatogenoIdentificado::class;
  }

  public function listMTPatogenoIdentificado()
  {
    $mtPatogenoIdentificado = $this->mtPatogenoIdentificado->orderBy('created_at', 'desc')->get();

    if (!isset($mtPatogenoIdentificado) || $mtPatogenoIdentificado == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay patogeno identificado que mostrar']);
    return response()->json($mtPatogenoIdentificado);
  }


  public function generateRandomString($length) {
    $arreglo = DB::table('mtpatogeno_identificado')->count();
    $last_patogeno_identificado = MTPatogenoIdentificado::all()->last();
    if (empty($last_patogeno_identificado))
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

  public function createMTPatogenoIdentificado($request)
  {
    $data = new MTPatogenoIdentificado([
      'codigo' => $this->generateRandomString(3), //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      'patogeno_identificado' => $request['patogeno_identificado']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTPatogenoIdentificado($id, Request $request)
  {
    $data = MTPatogenoIdentificado::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  public function eliminarMTPatogenoIdentificado($id)
  {
    MTPatogenoIdentificado::destroy($id);
    return response()->json('Patogeno identificado eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
