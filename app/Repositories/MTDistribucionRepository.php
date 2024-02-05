<?php


namespace App\Repositories;

use App\Models\MTDistribucion;
use App\Repositories\BaseRepository;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTDistribucionRepository extends BaseRepository
{

  private $mtDistribucion;

  public function __construct(MTDistribucion $mtDistribucion)
  {
    $this->mtDistribucion = $mtDistribucion;
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
    return MTDistribucion::class;
  }

  public function listMTDistribucion()
  {
    $mtDistribucion = $this->mtDistribucion->orderBy('created_at', 'desc')->get();
    if (!isset($mtDistribucion) || $mtDistribucion == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay Distribucion que mostrar']);
    return response()->json($mtDistribucion);
  }

  public function getMTDistribuciones()
  {
    return DB::table('mtdistribucion')
      ->orderBy('mtdistribucion.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTDistribucion($request)
  {
    $data = new MTDistribucion([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTDistribucion($id, Request $request)
  {
    $mtDistribucion = MTDistribucion::find($id);
    $mtDistribucion->update($request->all());
    return response()->json('Distribucion modificada');
  }

  public function eliminarMTDistribucion($id, Request $request)
  {
    $mtDistribucion = MTDistribucion::find($id);
    $mtDistribucion->delete($request->all());
    return response()->json('Distribucion  eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
