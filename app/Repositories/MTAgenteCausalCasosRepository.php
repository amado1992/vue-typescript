<?php

namespace App\Repositories;

use App\Models\MTAgenteCausalCaso;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTAgenteCausalCasoRepository
 * @package App\Repositories
 */
class MTAgenteCausalCasosRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id',
    'codigo',
    'agente_causal_caso',
    'tipocaso_id'
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
    return MTAgenteCausalCaso::class;
  }

  /**
   * Return a list of MTAgenteCausalCaso with pagination.
   **/
  public function listAgenteCausalCaso_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'casos:id,codigo,tipo_caso',
    ])
      ->where('codigo', 'like', '%' . $filter . '%')
      ->where('agente_causal_caso', 'like', '%' . $filter . '%')
      ->where('tipocaso_id', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTPAgenteCausalCaso.
   **/
  public function listAgenteCausalCaso_()
  {
    $query = $this->model->with([
      'casos:id,codigo,tipo_caso',
    ]);

    return $query->get();
  }
}
