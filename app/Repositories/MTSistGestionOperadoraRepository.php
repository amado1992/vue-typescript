<?php

namespace App\Repositories;

use App\Models\MTSistGestionOperadora;


/**
 * Class MTSistGestionOperadoraRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTSistGestionOperadoraRepository extends BaseRepository
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
    return MTSistGestionOperadora::class;
  }

  public function listOperadoras()
  {
    $operadoras  = $this->model->orderBy('desc_Operadora','asc')->get();

    if(!isset($operadoras) || $operadoras == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay operadoras que mostrar']);
    return $operadoras;
  }


}
