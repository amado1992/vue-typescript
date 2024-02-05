<?php

namespace App\Repositories;

use App\Models\MTSistGestionTiposSistGestion;


/**
 * Class MTSistGestionTiposSistGestionRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTSistGestionTiposSistGestionRepository extends BaseRepository
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
    return MTSistGestionTiposSistGestion::class;
  }

  public function listTiposSistGestion()
  {
    $tipos  = $this->model->orderBy('desc_TipoSistGestion','asc')->get();

    if(!isset($tipos) || $tipos == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay tipos de sistemas de gestión que mostrar']);
    return $tipos;
  }


}
