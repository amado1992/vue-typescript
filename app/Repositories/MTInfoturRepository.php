<?php

namespace App\Repositories;

use App\Models\MTInfotur;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTInfoturRepository
 * @package App\Repositories
 */
class MTInfoturRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id',
    'infotur',
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
    return MTInfotur::class;
  }

  /**
   * Return a list of MTInfotur with pagination.
   **/
  public function listInfotur_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'delegacionesterritoriales:id,delegacion_territorial',
    ])
      ->where('infotur', 'like', '%' . $filter . '%')
      ->where('direccion', 'like', '%' . $filter . '%')
      ->where('delegacion_territorial_id', 'like', '%' . $filter . '%')
      ->where('telefono', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTInfotur.
   **/
  public function listInfotur_()
  {
    $query = $this->model->with([
      'delegacionesterritoriales:id,delegacion_territorial',
    ])->where('activo',1);

    return $query->get();
  }
}
