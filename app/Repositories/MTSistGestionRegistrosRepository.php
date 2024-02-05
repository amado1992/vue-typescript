<?php

namespace App\Repositories;

use App\Models\MTSistGestionRegistros;
use Illuminate\Support\Facades\DB;


/**
 * Class MTSistGestionRegistrosRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTSistGestionRegistrosRepository extends BaseRepository
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
    return MTSistGestionRegistros::class;
  }

  public function listRegistros($id)
  {
    $registros  = $this->model
      ->with(
        'mtinstalacions:id,nombre,entidad_id',
        'mtinstalacions.entidades:id,osde_id',
        'mtinstalacions.entidades.osde:id,nombre',
        'mttipossistgests:id,desc_TipoSistGestion'

//        'entidads.mtinstalacions:entidad_id,municipio_id',
//        'entidads.mtinstalacions.municipios:id,provincia_id',
//        'entidads.mtinstalacions.municipios.provincia:id,nombre'
      )
      ->where('instalacion_id', $id)->orderBy('tipoSistGest_id','asc')->get();

    if(!isset($registros) || $registros == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resgistros que mostrar']);
    return $registros;
  }

  public function getRegistro($id)
  {
    $registros  = $this->model
      ->with(
        'mttipossistgests:id,desc_TipoSistGestion',


      )
      ->where('id', $id)->get();

    if(!isset($registros) || $registros == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
    return $registros;
  }

  public function getRegistersDemandas($id)
  {
    $registros = DB::table('mtsistgestionregistros')
      ->join('mtsistgestiontipossistgestion', 'mtsistgestiontipossistgestion.id', '=', 'tipoSistGest_id')
      ->join('mtsistgestionoperadoras', 'mtsistgestionoperadoras.id', '=', 'operadora_id')
      ->join('mtinstalacion', 'mtinstalacion.id', '=', 'instalacion_id')
      ->join('mtentidad', 'mtentidad.id', '=', 'mtinstalacion.entidad_id')
      ->join('mtosde', 'mtosde.id', '=', 'mtentidad.osde_id')
      ->select('mtsistgestionregistros.id as registro_id','mtsistgestiontipossistgestion.desc_TipoSistGestion as desc_TipoSistGestion',
        'mtinstalacion.nombre as nombre_instalacion','mtentidad.nombre as nombre_entidad','mtsistgestionregistros.estado_demanda as estado_demanda',
        'mtsistgestionregistros.alcance','mtsistgestionregistros.cant_trabajadores','mtsistgestionregistros.gestionado',
        'mtsistgestionregistros.fecha_auditoria','mtsistgestionregistros.conciliacion','mtsistgestionregistros.fecha_conciliacion','mtsistgestionregistros.estado','mtsistgestionoperadoras.desc_Operadora as desc_Operadora')
      ->where('mtosde.id', $id)
      ->get();

    if(!isset($registros) || $registros == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
    return $registros;
  }


}
