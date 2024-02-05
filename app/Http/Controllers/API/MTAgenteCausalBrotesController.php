<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTAgenteCausalBrote;
use App\Models\Traza;
use App\Repositories\MTAgenteCausalBrotesRepository;
use App\Http\Requests\API\CreateAgenteCausalBrotesAPIRequest;
use App\Http\Requests\API\UpdateAgenteCausalBrotesAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTAgenteCausalBrotesController extends AppBaseController
{
  /** @var MTAgenteCausalBrotesRepository */
  private $AgenteCausalBrotesRepository;

  /** @var MTAgenteCausalBrote */
  private $AgenteCausalBrotes;

  /** @var Traza */
  private $traza;

  public function __construct(MTAgenteCausalBrotesRepository $AgenteCausalBrotesRepository, MTAgenteCausalBrote $AgenteCausalBrotes, Traza $traza)
  {
    $this->AgenteCausalBrotesRepository = $AgenteCausalBrotesRepository;
    $this->AgenteCausalBrotes = $AgenteCausalBrotes;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTAgenteCausalBrotes
   *
   * @param Request $request
   * @return Response
   */
  public function listAgenteCausalBrotes()
  {
    try {
      $agente = $this->AgenteCausalBrotesRepository->listAgenteCausalBrote_();
      return $this->sendResponse($agente, __('messages.app.data_retrieved', ['model' => $this->AgenteCausalBrotes->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->AgenteCausalBrotes->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTAgenteCausalBrotes  whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $agente = $this->AgenteCausalBrotesRepository->listAgenteCausalBrote_paginate($input);
      return $this->sendResponse($agente, __('messages.app.data_retrieved', ['model' => $this->AgenteCausalBrotes->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->AgenteCausalBrotes->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTAgenteCausalBrotes in storage.
   *
   * @param CreateAgenteCausalBrotesAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtagente_causal_brotes')->count();
    $last_agente = MTAgenteCausalBrote::all()->last();
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

  public function store(CreateAgenteCausalBrotesAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'agente_causal_brote' => 'required|unique:mtagente_causal_brotes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3);

      $input = Arr::add($input, 'codigo', $codigo);
      $agente = $this->AgenteCausalBrotesRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->AgenteCausalBrotes->model, json_encode($agente));
      return $this->sendResponse($agente->toArray(), __('messages.app.model_success', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTAgenteCausalBrotes.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTAgenteCausalBrote $agente */
    $agente = $this->AgenteCausalBrotesRepository->find($id);

    if (empty($agente)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($agente->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTAgenteCausalBrotes in storage.
   *
   * @param int $id
   * @param UpdateAgenteCausalBrotesAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateAgenteCausalBrotesAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'agente_causal_brote' => 'required|unique:mtagente_causal_brotes,agente_causal_brote,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->except('codigo');

      /** @var MTAgenteCausalBrote $agente */
      $objActualizar = $agente = $this->AgenteCausalBrotesRepository->find($id);

      if (empty($agente)) {
        return $this->sendError('Dato no encontrado');
      }

      $agente = $this->AgenteCausalBrotesRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $agente];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->AgenteCausalBrotes->model, json_encode($objArray));
      return $this->sendResponse($agente->toArray(), __('messages.app.model_success', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTAgenteCausalBrotes from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTAgenteCausalBrote $agente */
      $agente = $this->AgenteCausalBrotesRepository->find($id);

      if (empty($agente)) {
        return $this->sendError('Dato no encontrado');
      }

      $agente->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->AgenteCausalBrotes->model, json_encode($agente));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->AgenteCausalBrotes->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}
