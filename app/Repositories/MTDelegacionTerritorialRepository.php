<?php

namespace App\Repositories;

use App\Models\MTDelegacionTerritorial;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTDelegacionTerritorialRepository
 * @package App\Repositories
 */
class MTDelegacionTerritorialRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id',
    'delegacion_territorial',
    'territorio_id'
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
    return MTDelegacionTerritorial::class;
  }

  /**
   * Return a list of MTDelegacionTerritorial with pagination.
   **/
  public function listDelegacionTerritorial_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'provincias:id,nombre',
    ])
      ->where('delegacion_territorial', 'like', '%' . $filter . '%')
      ->where('territorio_id', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTDelegacionTerritorial.
   **/
  public function listDelegacionTerritorial_()
  {
    $query = $this->model->with([
      'provincias:id,nombre',
    ])->where('activo',1);

    return $query->get();
  }
}
