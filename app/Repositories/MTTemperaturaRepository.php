<?php


namespace App\Repositories;

use App\Models\MTTemperatura;
use App\Repositories\BaseRepository;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTTemperaturaRepository extends BaseRepository
{

  private $mtTemperatura;

  public function __construct(MTTemperatura $mtTemperatura)
  {
    $this->mtTemperatura = $mtTemperatura;
  }

  protected $fieldSearchable = [
    'nombre',
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
    return MTTemperatura::class;
  }

  public function listMTTemperatura()
  {

    $mtTemperatura = $this->mtTemperatura->orderBy('created_at', 'desc')->get();
    if (!isset($mtTemperatura) || $mtTemperatura == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay Temperatura que mostrar']);
    return response()->json($mtTemperatura);
  }

  public function getMTTemperaturas()
  {
    return DB::table('mttemperatura')
      ->orderBy('mttemperatura.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTTemperatura($request)
  {
    $data = new MTTemperatura([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTTemperatura($id, Request $request)
  {
    $mtTemperatura = MTTemperatura::find($id);
    $mtTemperatura->update($request->all());
    return response()->json('Temperatura modificada');
  }

  public function eliminarMTTemperatura($id, Request $request)
  {
    $mtTemperatura = MTTemperatura::find($id);
    $mtTemperatura->delete($request->all());
    return response()->json('Temperatura  eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
