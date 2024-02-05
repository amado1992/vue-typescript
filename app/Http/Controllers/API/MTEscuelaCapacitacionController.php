<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTEscuelaCapacitacion;
use App\Models\Traza;
use App\Repositories\MTEscuelaCapacitacionRepository;
use App\Http\Requests\API\CreateMTEscuelaCapacitacionAPIRequest;
use App\Http\Requests\API\UpdateMTEscuelaCapacitacionAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTEscuelaCapacitacionController extends AppBaseController
{
  /** @var MTEscuelaCapacitacionRepository */
  private $EscuelaCapacitacionRepository;

  /** @var MTEscuelaCapacitacion */
  private $EscuelaCapacitacion;

  /** @var Traza */
  private $traza;

  public function __construct(MTEscuelaCapacitacionRepository $EscuelaCapacitacionRepository, MTEscuelaCapacitacion $EscuelaCapacitacion, Traza $traza)
  {
    $this->EscuelaCapacitacionRepository = $EscuelaCapacitacionRepository;
    $this->EscuelaCapacitacion = $EscuelaCapacitacion;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTEscuelaCapacitacion
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $escuela = $this->EscuelaCapacitacionRepository->listEscuelaCapacitacion_();
      return $this->sendResponse($escuela, __('messages.app.data_retrieved', ['model' => $this->EscuelaCapacitacion->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->EscuelaCapacitacion->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTEscuelaCapacitacion  whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $agente = $this->EscuelaCapacitacionRepository->listEscuelaCapacitacion_paginate($input);
      return $this->sendResponse($agente, __('messages.app.data_retrieved', ['model' => $this->EscuelaCapacitacion->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->EscuelaCapacitacion->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTEscuelaCapacitacion in storage.
   *
   * @param CreateMTEscuelaCapacitacionAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTEscuelaCapacitacionAPIRequest $request)
  {
      $validate = validator($request->all(), [
        'escuela_capacitacion' => 'required|unique:mtescuela_capacitacions'
      ]);

      if ($validate->fails())
      {
        Log::error('Existe un registro con ese nombre');
        return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'creada']), '500');
      }

    try {
      $input = $request->all();
      $escuela = $this->EscuelaCapacitacionRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->EscuelaCapacitacion->model, json_encode($escuela));
      return $this->sendResponse($escuela->toArray(), __('messages.app.model_success', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTEscuelaCapacitacion.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTEscuelaCapacitacion $escuela */
    $escuela = $this->EscuelaCapacitacionRepository->find($id);

    if (empty($escuela)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($escuela->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTEscuelaCapacitacion in storage.
   *
   * @param int $id
   * @param UpdateMTEscuelaCapacitacionAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTEscuelaCapacitacionAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'escuela_capacitacion' => 'required|unique:mtescuela_capacitacions,escuela_capacitacion,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTEscuelaCapacitacion $escuela */
      $objActualizar = $escuela = $this->EscuelaCapacitacionRepository->find($id);

      if (empty($escuela)) {
        return $this->sendError('Dato no encontrado');
      }

      $escuela = $this->EscuelaCapacitacionRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $escuela];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->EscuelaCapacitacion->model, json_encode($objArray));
      return $this->sendResponse($escuela->toArray(), __('messages.app.model_success', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTEscuelaCapacitacion from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTEscuelaCapacitacion $escuela */
      $escuela = $this->EscuelaCapacitacionRepository->find($id);

      if (empty($escuela)) {
        return $this->sendError('Dato no encontrado');
      }

      $escuela->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->EscuelaCapacitacion->model, json_encode($escuela));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->EscuelaCapacitacion->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}
