<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTAgenteCausalCaso;
use App\Models\Traza;
use App\Repositories\MTAgenteCausalCasosRepository;
use App\Http\Requests\API\CreateAgenteCausalCasosAPIRequest;
use App\Http\Requests\API\UpdateAgenteCausalCasosAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTAgenteCausalCasosController extends AppBaseController
{
  /** @var MTAgenteCausalCasosRepository */
  private $AgenteCausalCasosRepository;

  /** @var MTAgenteCausalCaso */
  private $AgenteCausalCasos;

  /** @var Traza */
  private $traza;

  public function __construct(MTAgenteCausalCasosRepository $AgenteCausalCasosRepository, MTAgenteCausalCaso $AgenteCausalCasos, Traza $traza)
  {
    $this->AgenteCausalCasosRepository = $AgenteCausalCasosRepository;
    $this->AgenteCausalCasos = $AgenteCausalCasos;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTAgenteCausalCasos
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $AgenteCausalCasos = $this->AgenteCausalCasosRepository->listAgenteCausalCaso_();
      return $this->sendResponse($AgenteCausalCasos, __('messages.app.data_retrieved', ['model' => $this->AgenteCausalCasos->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->AgenteCausalCasos->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTAgenteCausalCasos  whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $agente = $this->AgenteCausalCasosRepository->listAgenteCausalCaso_paginate($input);
      return $this->sendResponse($agente, __('messages.app.data_retrieved', ['model' => $this->AgenteCausalCasos->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->AgenteCausalCasos->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTAgenteCausalCasos in storage.
   *
   * @param CreateAgenteCausalCasosAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtagente_causal_casos')->count();
    $last_agente = MTAgenteCausalCaso::all()->last();
    if (empty($last_agente))
      $cont = 1;
    else
      $cont = $arreglo + 1;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.$cont;
  }

  public function store(CreateAgenteCausalCasosAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'agente_causal_caso' => 'required|unique:mtagente_causal_casos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3);
      $input = Arr::add($input, 'codigo', $codigo);
      $agente = $this->AgenteCausalCasosRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->AgenteCausalCasos->model, json_encode($agente));
      return $this->sendResponse($agente->toArray(), __('messages.app.model_success', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTAgenteCausalCasos.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTAgenteCausalCaso $agente */
    $agente = $this->AgenteCausalCasosRepository->find($id);

    if (empty($agente)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($agente->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTAgenteCausalCasos in storage.
   *
   * @param int $id
   * @param UpdateAgenteCausalCasosAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateAgenteCausalCasosAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'agente_causal_caso' => 'required|unique:mtagente_causal_casos,agente_causal_caso,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->except('codigo');

      /** @var MTAgenteCausalCaso $agente */
      $objActualizar = $agente = $this->AgenteCausalCasosRepository->find($id);

      if (empty($agente)) {
        return $this->sendError('Dato no encontrado');
      }

      $agente = $this->AgenteCausalCasosRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $agente];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->AgenteCausalCasos->model, json_encode($objArray));
      return $this->sendResponse($agente->toArray(), __('messages.app.model_success', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTAgenteCausalCasos from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTAgenteCausalCaso $agente */
      $agente = $this->AgenteCausalCasosRepository->find($id);

      if (empty($agente)) {
        return $this->sendError('Dato no encontrado');
      }

      $agente->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->AgenteCausalCasos->model, json_encode($agente));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->AgenteCausalCasos->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

