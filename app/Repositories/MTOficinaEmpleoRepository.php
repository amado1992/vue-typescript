<?php

namespace App\Repositories;

use App\Models\MTOficinaEmpleo;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTOficinaEmpleoRepository
 * @package App\Repositories
 */
class MTOficinaEmpleoRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id',
    'oficina_empleo',
    'direccion',
    'delegacion_territorial_id',
    'telefono'
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
    return MTOficinaEmpleo::class;
  }

  /**
   * Return a list of MTOficinaEmpleo with pagination.
   **/
  public function listOficinaEmpleo_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'delegacionesterritoriales:id,delegacion_territorial',
    ])
      ->where('oficina_empleo', 'like', '%' . $filter . '%')
      ->where('direccion', 'like', '%' . $filter . '%')
      ->where('delegacion_territorial_id', 'like', '%' . $filter . '%')
      ->where('telefono', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTOficinaEmpleo.
   **/
  public function listOficinaEmpleo_()
  {
    $query = $this->model->with([
      'delegacionesterritoriales:id,delegacion_territorial',
    ])->where('activo',1);

    return $query->get();
  }
}
