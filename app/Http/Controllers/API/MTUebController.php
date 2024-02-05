<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTUeb;
use App\Models\Traza;
use App\Repositories\MTUebRepository;
use App\Http\Requests\API\CreateMTUebAPIRequest;
use App\Http\Requests\API\UpdateMTUebAPIRequest;
use Illuminate\Support\Facades\Log;

class MTUebController extends AppBaseController
{
  /** @var MTUebRepository */
  private $UebRepository;

  /** @var MTUeb */
  private $Ueb;

  /** @var Traza */
  private $traza;

  public function __construct(MTUebRepository $UebRepository, MTUeb $Ueb, Traza $traza)
  {
    $this->UebRepository = $UebRepository;
    $this->Ueb = $Ueb;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTUeb
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $data = $this->UebRepository->listUeb_();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Ueb->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Ueb->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTUeb whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $data = $this->UebRepository->listUeb_paginate($input);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Ueb->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Ueb->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTUeb in storage.
   *
   * @param CreateMTUebAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTUebAPIRequest $request)
  {
      $validate = validator($request->all(), [
        'ueb' => 'required|unique:mtuebs'
      ]);

      if ($validate->fails())
      {
        Log::error('Existe un registro con ese nombre');
        return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Ueb->model, 'operation' => 'creada']), '500');
      }

    try {
      $input = $request->all();
      $data = $this->UebRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Ueb->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Ueb->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Ueb->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTUeb.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTUeb $data */
    $data = $this->UebRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($data->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTUeb in storage.
   *
   * @param int $id
   * @param UpdateMTUebAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTUebAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'ueb' => 'required|unique:mtuebs,ueb,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Ueb->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTUeb $data */
      $objActualizar = $data = $this->UebRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data = $this->UebRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Ueb->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Ueb->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Ueb->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTUeb from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTUeb $data */
      $data = $this->UebRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Ueb->model, json_encode($data));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Ueb->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

