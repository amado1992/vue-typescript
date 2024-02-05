<?php

namespace App\Repositories;

use App\Models\MTCostoCalidadReport;
use App\Models\MTPremio;
use App\Models\MTSistGestionEstadosDemanda;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;


/**
 * Class MTSistGestionEstadoDemandaRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTSistGestionEstadoDemandaRepository extends BaseRepository
{


  /**
   * @var array
   */
  protected $fieldSearchable = [
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

  /**
   * Configure the Model
   **/
  public function model()
  {
    return MTSistGestionEstadosDemanda::class;
  }




  public function listEstadosDemanda()
  {
    //$estados = MTSistGestionEstadosDemanda::query();
    $estados  = $this->model->orderBy('desc_EstadoDemanda','asc')->get();

    if(!isset($estados) || $estados == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay estados que mostrar']);
    //return response()->json(['alert' => true, 'data' => $estados, 'msg' => 'No hay estados que mostrar']);
    //return response()->json($estados);
    //return $estados;
    return $estados;

    //return DB::table($estados);
  }

//  public function updateEstadoDemanda(Request $request)
//  {
//    $costo = MTSistGestionEstadosDemanda::findOrFail($request['id']);
//
//    $costo->desc_EstadoDemanda = $request->data['desc_EstadoDemanda'];
//
//    if ($costo->save()){
//      return $costo;
//    }
////    $estado = MTSistGestionEstadosDemanda::find($request['id_EstadoDemanda']);
////    $estado->desc_EstadoDemanda = $request->input('desc_EstadoDemanda');
////    $estado->save();
////    //return response()->json($estado);
////    return $estado;
//  }


}
