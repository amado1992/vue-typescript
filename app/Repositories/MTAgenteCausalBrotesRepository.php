<?php

namespace App\Repositories;

use App\Models\MTAgenteCausalBrote;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Utils\EventsUtil;
use stdClass;

/**
 * Class MTAgenteCausalBrotesRepository
 * @package App\Repositories
 */
class MTAgenteCausalBrotesRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'id',
    'codigo',
    'agente_causal_brote',
    'tipobrote_id'
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
    return MTAgenteCausalBrote::class;
  }

  /**
   * Return a list of MTAgenteCausalBrote with pagination.
   **/
  public function listAgenteCausalBrote_paginate($request)
  {
    $filter = $request['search'];
    return $this->model->with([
      'brotes:id,codigo,tipo_brote',
    ])
      ->where('codigo', 'like', '%' . $filter . '%')
      ->where('agente_causal_brote', 'like', '%' . $filter . '%')
      ->where('tipobrote_id', 'like', '%' . $filter . '%')
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  /**
   * Return a list of MTAgenteCausalBrote.
   **/
  public function listAgenteCausalBrote_()
  {
    $query = $this->model->with([
      'brotes:id,codigo,tipo_brote',
    ]);

    return $query->get();
  }
}
