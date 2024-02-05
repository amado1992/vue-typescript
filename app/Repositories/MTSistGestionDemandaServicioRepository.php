<?php

namespace App\Repositories;

use App\Models\MTSistGestionDemandaServicio;


/**
 * Class MTSistGestionDemandaServicioRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTSistGestionDemandaServicioRepository extends BaseRepository
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
    return MTSistGestionDemandaServicio::class;
  }

  public function listDemandaServicios()
  {
    $demandaservicios  = $this->model->orderBy('desc_DemandaServicio','asc')->get();

    if(!isset($demandaservicios) || $demandaservicios == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay demandas de servicios que mostrar']);
    return $demandaservicios;
  }


}
