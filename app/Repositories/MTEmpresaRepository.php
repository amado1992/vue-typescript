<?php

namespace App\Repositories;

use App\Models\MTEmpresa;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTEmpresaRepository
 * @package App\Repositories
 */
class MTEmpresaRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id','activo', 'empresa', 'osde_id', 'direccion', 'correo', 'telefono'
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
    return MTEmpresa::class;
  }

  /**
   * Return a list of MTAgenteCausalCaso with pagination.
   **/
  public function listEmpresa_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'osdes:id,nombre',
    ])
      ->where('empresa', 'like', '%' . $filter . '%')
      ->where('tipo_empresa', 'like', '%' . $filter . '%')
      ->where('osde_id', 'like', '%' . $filter . '%')
      ->where('direccion', 'like', '%' . $filter . '%')
      ->where('correo', 'like', '%' . $filter . '%')
      ->where('telefono', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTEmpresa.
   **/
  public function listEmpresa_()
  {
    $query = $this->model->with([
      'osdes:id,nombre',
    ])->where('activo',1);

    return $query->get();
  }

  public function listEmpresa_importadora()
  {
    $query = $this->model->with([
      'osdes:id,nombre',
    ])->where('activo',1)->where('tipo_empresa','=','Importadora');

    return $query->get();
  }
}
