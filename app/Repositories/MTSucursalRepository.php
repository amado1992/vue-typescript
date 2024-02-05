<?php

namespace App\Repositories;

use App\Models\MTSucursal;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTSucursalRepository
 * @package App\Repositories
 */
class MTSucursalRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id', 'activo', 'sucursal', 'complejo', 'empresa_id', 'direccion', 'correo', 'telefono'
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
    return MTSucursal::class;
  }

  /**
   * Return a list of MTSucursal with pagination.
   **/
  public function listSucursal_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'empresas:id,empresa',
    ])
      ->where('sucursal', 'like', '%' . $filter . '%')
      //->where('complejo', 'like', '%' . $filter . '%')
      ->where('empresa_id', 'like', '%' . $filter . '%')
      ->where('direccion', 'like', '%' . $filter . '%')
      ->where('correo', 'like', '%' . $filter . '%')
      ->where('telefono', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTSucursal.
   **/
  public function listSucursal_()
  {
    $query = $this->model->with([
      'empresas:id,empresa',
    ])->where('activo',1);

    return $query->get();
  }
}
