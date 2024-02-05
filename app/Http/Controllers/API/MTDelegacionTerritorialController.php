<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTDelegacionTerritorial;
use App\Models\Traza;
use App\Repositories\MTDelegacionTerritorialRepository;
use App\Http\Requests\API\CreateMTDelegacionTerritorialAPIRequest;
use App\Http\Requests\API\UpdateMTDelegacionTerritorialAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTDelegacionTerritorialController extends AppBaseController
{
  /** @var MTDelegacionTerritorialRepository */
  private $DelegacionTerritorialRepository;

  /** @var MTDelegacionTerritorial */
  private $DelegacionTerritorial;

  /** @var Traza */
  private $traza;

  public function __construct(MTDelegacionTerritorialRepository $DelegacionTerritorialRepository, MTDelegacionTerritorial $DelegacionTerritorial, Traza $traza)
  {
    $this->DelegacionTerritorialRepository = $DelegacionTerritorialRepository;
    $this->DelegacionTerritorial = $DelegacionTerritorial;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTDelegacionTerritorial
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $delegacion = $this->DelegacionTerritorialRepository->listDelegacionTerritorial_();
      return $this->sendResponse($delegacion, __('messages.app.data_retrieved', ['model' => $this->DelegacionTerritorial->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->DelegacionTerritorial->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTDelegacionTerritorial  whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $delegacion = $this->DelegacionTerritorialRepository->listDelegacionTerritorial_paginate($input);
      return $this->sendResponse($delegacion, __('messages.app.data_retrieved', ['model' => $this->DelegacionTerritorial->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->DelegacionTerritorial->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTDelegacionTerritorial in storage.
   *
   * @param CreateMTDelegacionTerritorialAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTDelegacionTerritorialAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'delegacion_territorial' => 'required|unique:mtdelegacion_territorials'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $delegacion = $this->DelegacionTerritorialRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->DelegacionTerritorial->model, json_encode($delegacion));
      return $this->sendResponse($delegacion->toArray(), __('messages.app.model_success', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTDelegacionTerritorial.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTDelegacionTerritorial $delegacion */
    $delegacion = $this->DelegacionTerritorialRepository->find($id);

    if (empty($delegacion)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($delegacion->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTDelegacionTerritorial in storage.
   *
   * @param int $id
   * @param UpdateMTDelegacionTerritorialAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTDelegacionTerritorialAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'delegacion_territorial' => 'required|unique:mtdelegacion_territorials,delegacion_territorial,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTDelegacionTerritorial $delegacion */
      $objActualizar = $delegacion = $this->DelegacionTerritorialRepository->find($id);

      if (empty($delegacion)) {
        return $this->sendError('Dato no encontrado');
      }

      $delegacion = $this->DelegacionTerritorialRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $delegacion];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->DelegacionTerritorial->model, json_encode($objArray));
      return $this->sendResponse($delegacion->toArray(), __('messages.app.model_success', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTDelegacionTerritorial from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTDelegacionTerritorial $delegacion */
      $delegacion = $this->DelegacionTerritorialRepository->find($id);

      if (empty($delegacion)) {
        return $this->sendError('Dato no encontrado');
      }

      $delegacion->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->DelegacionTerritorial->model, json_encode($delegacion));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->DelegacionTerritorial->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}
