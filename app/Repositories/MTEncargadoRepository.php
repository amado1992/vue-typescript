<?php

namespace App\Repositories;

use App\Models\MTEncargado;
use Illuminate\Http\Request;

class MTEncargadoRepository
{
  private $mtEncargado;

  public function __construct(MTEncargado $mtEncargado)
  {
    $this->mtEncargado = $mtEncargado;
  }

  protected $fieldSearchable = [
    'nombre',
    'apellidos',
    'email',
    'telefono',
    'capacitacion',
    'foto',
    'almacen_id'
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
    return MTEncargado::class;
  }

  public function listMTEncargado()
  {

    $mtEncargado = $this->mtEncargado->with(['almacenes:id,nombre'])
      ->orderBy('created_at', 'desc')->get();

    if (!isset($mtEncargado) || $mtEncargado == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay encargados que mostrar']);
    return response()->json($mtEncargado);

  }

  public function createMTEncargado($request)
  {
    $data = new MTEncargado([
      'nombre' => $request['nombre'],
      'apellidos' => $request['apellidos'],
      'email' => $request['email'],
      'telefono' => $request['telefono'],
      'capacitacion' => $request['capacitacion'],
      'foto' => $request['foto'],
      'almacen_id' => $request['almacen_id']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTEncargado($id, Request $request)
  {
    $mtEncargado = MTEncargado::find($id);
    $mtEncargado->update($request->all());
    return response()->json('Encargado modificado');
  }

  public function eliminarMTEncargado($id, Request $request)
  {
    $mtEncargado = MTEncargado::find($id);
    $mtEncargado->delete($request->all());
    return response()->json('Encargado  eliminado');
  }

  /**
   * Configure the Model
   *
   * @return string
   */

}
