<?php

namespace App\Repositories;

use App\Models\MTUeb;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTUebRepository
 * @package App\Repositories
 */
class MTUebRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id', 'activo', 'ueb', 'empresa_id', 'direccion', 'correo', 'telefono'
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
    return MTUeb::class;
  }

  /**
   * Return a list of MTUeb with pagination.
   **/
  public function listUeb_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'empresas:id,empresa',
    ])
      ->where('ueb', 'like', '%' . $filter . '%')
      ->where('empresa_id', 'like', '%' . $filter . '%')
      ->where('direccion', 'like', '%' . $filter . '%')
      ->where('correo', 'like', '%' . $filter . '%')
      ->where('telefono', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTUeb.
   **/
  public function listUebCaso_()
  {
    $query = $this->model->with([
      'empresas:id,empresa',
    ])->where('activo',1);

    return $query->get();
  }
}
