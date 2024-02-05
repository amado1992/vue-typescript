<?php


namespace App\Repositories;

use App\Models\MTCategoriaPremio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MTCategoriaPremioRepository extends BaseRepository
{

  private $mtCategoriaPremio;

  public function __construct(MTCategoriaPremio $mtCategoriaPremio)
  {
    $this->mtCategoriaPremio = $mtCategoriaPremio;
  }

  protected $fieldSearchable = [
    'nombre'
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
    return MTCategoriaPremio::class;
  }

  public function listMTCategoriaPremio()
  {
    $mtCategoriaPremio = $this->mtCategoriaPremio->orderBy('created_at', 'desc')->get();

    if (!isset($mtCategoriaPremio) || $mtCategoriaPremio == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay categorías de premios que mostrar']);
    return response()->json($mtCategoriaPremio);
  }

  public function create($request)
  {
    $data = new MTCategoriaPremio([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function getCategoria_premios()
  {
    return DB::table('mtcategoriapremio')
      ->orderBy('mtcategoriapremio.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function updateMTCategoriaPremio($id, Request $request)
  {
    $premio = MTCategoriaPremio::find($id);
    $premio->nombre = $request->input('nombre');
    $premio->save();
    return response()->json($premio);
  }

  public function eliminarMTCategoriaPremio($id)
  {
    MTCategoriaPremio::destroy($id);
    return response()->json('Categoría de premio eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
