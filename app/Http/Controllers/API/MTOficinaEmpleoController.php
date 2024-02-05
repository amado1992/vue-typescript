<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTOficinaEmpleo;
use App\Models\Traza;
use App\Repositories\MTOficinaEmpleoRepository;
use App\Http\Requests\API\CreateMTOficinaEmpleoAPIRequest;
use App\Http\Requests\API\UpdateMTOficinaEmpleoAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTOficinaEmpleoController extends AppBaseController
{
  /** @var MTOficinaEmpleoRepository */
  private $OficinaEmpleoRepository;

  /** @var MTOficinaEmpleo */
  private $OficinaEmpleo;

  /** @var Traza */
  private $traza;

  public function __construct(MTOficinaEmpleoRepository $OficinaEmpleoRepository, MTOficinaEmpleo $OficinaEmpleo, Traza $traza)
  {
    $this->OficinaEmpleoRepository = $OficinaEmpleoRepository;
    $this->OficinaEmpleo = $OficinaEmpleo;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTOficinaEmpleo
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $data = $this->OficinaEmpleoRepository->listOficinaEmpleo_();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->OficinaEmpleo->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->OficinaEmpleo->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTOficinaEmpleo whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $data = $this->OficinaEmpleoRepository->listOficinaEmpleo_paginate($input);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->OficinaEmpleo->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->OficinaEmpleo->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTOficinaEmpleo in storage.
   *
   * @param CreateMTOficinaEmpleoAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTOficinaEmpleoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'oficina_empleo' => 'required|unique:mtoficina_empleos'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->OficinaEmpleo->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->OficinaEmpleoRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->OficinaEmpleo->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->OficinaEmpleo->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OficinaEmpleo->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTOficinaEmpleo.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTOficinaEmpleo $data */
    $data = $this->OficinaEmpleoRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($data->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTOficinaEmpleo in storage.
   *
   * @param int $id
   * @param UpdateMTOficinaEmpleoAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTOficinaEmpleoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'oficina_empleo' => 'required|unique:mtoficina_empleos,oficina_empleo,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->OficinaEmpleo->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTOficinaEmpleo $data */
      $objActualizar = $data = $this->OficinaEmpleoRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data = $this->OficinaEmpleoRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->OficinaEmpleo->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->OficinaEmpleo->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OficinaEmpleo->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTOficinaEmpleo from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTOficinaEmpleo $data */
      $data = $this->OficinaEmpleoRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->OficinaEmpleo->model, json_encode($data));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OficinaEmpleo->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

