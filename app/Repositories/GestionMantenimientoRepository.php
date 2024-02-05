<?php

namespace App\Repositories;

use App\Models\MTGestionMantenimiento;
use App\Repositories\BaseRepository;

/**
 * Class PlanMantenimientoRepository
 * @package App\Repositories
 * @version June 1, 2021, 4:27 pm CDT
*/

class GestionMantenimientoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
      'descripcion',
      'instalacion_id',
      'anno',
      'mes',
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
        return MTGestionMantenimiento::class;
    }

  public function findExist($request)
  {
    $descripcion = $request['descripcion'];
    $instalacion_id = $request['instalacion_id'];
    $anno = $request['anno'];
    $mes = $request['mes'];

    $registros  = $this->model->where([
      ['descripcion', '=', $descripcion],
      ['instalacion_id', '=', $instalacion_id],
      ['anno', '=', $anno],
      ['mes', '=', $mes],
    ])->first();

    if(!isset($registros))
      return false;
    return true;
  }

    public function getAnexosxInstalacion($request)
    {
      $id = $request;
      $registros  = $this->model
        ->where('instalacion_id',$id)
        ->orderBy('anno','desc')
        ->orderBy('mes','desc')
        ->get();

      if(!isset($registros) || $registros == null)
        return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay registros que mostrar']);
      return $registros;
    }

    public function getAnexos2All()
    {
      $registros  = $this->model->with(
        'planmantenimientos:gestionmtto_id,porCientoHFOHDD',
        'instalacions:id,entidad_id,municipio_id,nombre',
        'instalacions.entidades:id,nombre',
        'instalacions.municipio:id,provincia_id,nombre',
        'instalacions.municipio.provincia:id,nombre')
        ->where('descripcion', 'Anexo 2')
        ->orderByDesc('anno','mes')
        ->get();
      if(!isset($registros) || $registros == null)
        return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resgistros que mostrar']);
      return $registros;
    }
}
