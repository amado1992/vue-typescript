<?php

namespace App\Repositories;

use App\Models\MTKmRecorridos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use stdClass;

class MTKmRecorridosRepository
{
  private $mtKmRecorridos;

  public function __construct(MTKmRecorridos $mtKmRecorridos)
  {
    $this->mtKmRecorridos = $mtKmRecorridos;
  }

  protected $fieldSearchable = [
    'mes', 'km_recorridos'
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
    return MTKmRecorridos::class;
  }

  public function listMTKmRecorridos()
  {
    $mtKmRecorridos = $this->mtKmRecorridos
      ->with(['vehiculos:id,marca,modelo,matricula'])->orderBy('created_at', 'desc')->get();

    if (!isset($mtKmRecorridos) || $mtKmRecorridos == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay KmRecorridos que mostrar']);
    return response()->json($mtKmRecorridos);
  }

  public function createMTKmRecorridos($request)
  {
    $data = new MTKmRecorridos([
      'mes' => $request['mes'],
      'anno' => $request['anno'],
      'km_recorridos' => $request['km_recorridos'],
      'vehiculo_empresa_id' => $request['vehiculo_empresa_id']
    ]);
    $data->save();
    return response()->json($data);
  }

  public function updateMTKmRecorridos($id, Request $request)
  {
    $data = MTKmRecorridos::find($id);
    $data->update($request->all());
    return response()->json($data);
  }

  /**
   * Devuelve un array con el estado de los km recorridos y la informacion del vehiculo.
   * @return Array
   */
  public function comprobarExistenciaKmRecorridos()
  {
    $data = DB::table('mtkm_recorridos')
      ->join('mtmedio_transportes', 'vehiculo_empresa_id', '=', 'mtmedio_transportes.id')
      ->select(
        'mtkm_recorridos.mes',
        'mtmedio_transportes.instalacion_id AS instalacion_id',
        'mtmedio_transportes.modelo AS vehiculo_modelo',
        'mtmedio_transportes.marca AS vehiculo_marca',
        'mtmedio_transportes.tipo_flota AS vehiculo_tipo_flota',
        'mtmedio_transportes.tipo_medio_transporte AS vehiculo_tipo_medio_transporte',
        'mtmedio_transportes.matricula AS vehiculo_matricula',
      )
      ->get();

    $vehiculos = DB::table('mtmedio_transportes')->get();

    if ($vehiculos->isNotEmpty()) {
      if ($data->isNotEmpty()) {
        $date = now()->toArray();
        foreach ($data as $key => $value) {
          $response[$key] = new stdClass();
          if ($value->mes < $date['month']) {
            $response[$key] = $value;
            $response[$key]->status = 'vencido';
          } else {
            $response[$key] = $value;
            $response[$key]->status = 'activo';
          }
        }
        return $response;
      } else {
        $response[] = new stdClass();
        $response[0]->status = 'no_asignado';
        return $response;
      }
    } else {
      $response[] = new stdClass();
      $response[0]->status = 'clean';
      return $response;
    }
  }

  /**
   * Configure the Model
   *
   * @return string
   */
}
