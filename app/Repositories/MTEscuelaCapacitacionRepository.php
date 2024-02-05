<?php

namespace App\Repositories;

use App\Models\MTEscuelaCapacitacion;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTEscuelaCapacitacionRepository
 * @package App\Repositories
 */
class MTEscuelaCapacitacionRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id',
    'escuela_capacitacion',
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
    return MTEscuelaCapacitacion::class;
  }

  /**
   * Return a list of MTEscuelaCapacitacion with pagination.
   **/
  public function listEscuelaCapacitacion_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'delegacionesterritoriales:id,delegacion_territorial',
    ])
      ->where('escuela_capacitacion', 'like', '%' . $filter . '%')
      ->where('direccion', 'like', '%' . $filter . '%')
      ->where('delegacion_territorial_id', 'like', '%' . $filter . '%')
      ->where('telefono', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTEscuelaCapacitacion.
   **/
  public function listEscuelaCapacitacion_()
  {
    $query = $this->model->with([
      'delegacionesterritoriales:id,delegacion_territorial',
    ])->where('activo',1);

    return $query->get();
  }
}
