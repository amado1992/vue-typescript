<?php


namespace App\Repositories;


use App\Models\MTClasificacionEvento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \stdClass;

class MTClasificacionEventoRepository
{
  private $mtClasificacionEvento;

  public function __construct(MTClasificacionEvento $mtClasificacionEvento)
  {
    $this->mtClasificacionEvento = $mtClasificacionEvento;
  }

  protected $fieldSearchable = [
    'codigo', 'clasificacion_evento'
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
    return MTClasificacionEvento::class;
  }

  public function listMTClasificacionEvento()
  {
    $mtClasificacionEvento = $this->mtClasificacionEvento->orderBy('created_at', 'desc')->get();

    if (!isset($mtClasificacionEvento) || $mtClasificacionEvento == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay clasificaciones de eventos que mostrar']);
    return response()->json($mtClasificacionEvento);
  }

  public function generateRandomString($length) {
    $arreglo = DB::table('mtclasificacion_eventos')->count();
    $last_clasificacion_evento = MTClasificacionEvento::all()->last();
    if (empty($last_clasificacion_evento))
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

  public function createMTClasificacionEvento($request)
  {
    $data = new MTClasificacionEvento([
      'codigo' => $this->generateRandomString(3),
      'clasificacion_evento' => $request['clasificacion_evento']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTClasificacionEvento($id, Request $request)
  {
    $data = MTClasificacionEvento::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  public function eliminarMTClasificacionEvento($id)
  {
    MTClasificacionEvento::destroy($id);
    return response()->json('Clasificacion de evento eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
