<?php


namespace App\Repositories;

use App\Models\MTCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MTCategoriaRepository
{

  private $mtCategoria;

  public function __construct(MTCategoria $mtCategoria)
  {
    $this->mtCategoria = $mtCategoria;
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
    return MTCategoria::class;
  }

  public function listMTCategoria()
  {
    $mtCategoria = $this->mtCategoria->orderBy('created_at', 'desc')->get();

    if (!isset($mtCategoria) || $mtCategoria == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay categorias que mostrar']);
    return response()->json($mtCategoria);

  }

  public function getMTCategorias()
  {
    return DB::table('mtcategoria')
      ->orderBy('mtcategoria.created_at', 'desc')
      ->select('*')
      ->get();
  }

  public function createMTCategoria($request)
  {
    $data = new MTCategoria([
      'nombre' => $request['nombre']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTCategoria($id, Request $request)
  {
    $mtCategoria = MTCategoria::find($id);
    $mtCategoria->update($request->all());
    return response()->json('Categoria modificada');
  }

  public function eliminarMTCategoria($id, Request $request)
  {
    $mtCategoria = MTCategoria::find($id);
    $mtCategoria->delete($request->all());
    return response()->json('Categoria eliminada');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
