<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTSucursal;
use App\Models\Traza;
use App\Repositories\MTSucursalRepository;
use App\Http\Requests\API\CreateMTSucursalAPIRequest;
use App\Http\Requests\API\UpdateMTSucursalAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTSucursalController extends AppBaseController
{
  /** @var MTSucursalRepository */
  private $SucursalRepository;

  /** @var MTSucursal */
  private $Sucursal;

  /** @var Traza */
  private $traza;

  public function __construct(MTSucursalRepository $SucursalRepository, MTSucursal $Sucursal, Traza $traza)
  {
    $this->SucursalRepository = $SucursalRepository;
    $this->Sucursal = $Sucursal;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTSucursal
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $data = $this->SucursalRepository->listSucursal_();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Sucursal->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Sucursal->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTSucursal whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $data = $this->SucursalRepository->listSucursal_paginate($input);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Sucursal->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Sucursal->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTSucursal in storage.
   *
   * @param CreateMTSucursalAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTSucursalAPIRequest $request)
  {
      $validate = validator($request->all(), [
        'sucursal' => 'required|unique:mtsucursals'
      ]);

      if ($validate->fails())
      {
        Log::error('Existe un registro con ese nombre');
        return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Sucursal->model, 'operation' => 'creada']), '500');
      }

    try {
      $input = $request->all();
      $data = $this->SucursalRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Sucursal->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Sucursal->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Sucursal->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTSucursal.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTSucursal $data */
    $data = $this->SucursalRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($data->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTSucursal in storage.
   *
   * @param int $id
   * @param UpdateMTSucursalAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTSucursalAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'sucursal' => 'required|unique:mtsucursals,sucursal,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Sucursal->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTSucursal $data */
      $objActualizar = $data = $this->SucursalRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data = $this->SucursalRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Sucursal->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Sucursal->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Sucursal->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTSucursal from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTSucursal $data */
      $data = $this->SucursalRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Sucursal->model, json_encode($data));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Sucursal->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

