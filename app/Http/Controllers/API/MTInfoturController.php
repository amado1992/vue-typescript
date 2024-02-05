<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTInfotur;
use App\Models\Traza;
use App\Repositories\MTInfoturRepository;
use App\Http\Requests\API\CreateMTInfoturAPIRequest;
use App\Http\Requests\API\UpdateMTInfoturAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTInfoturController extends AppBaseController
{
  /** @var MTInfoturRepository */
  private $InfoturRepository;

  /** @var MTInfotur */
  private $Infotur;

  /** @var Traza */
  private $traza;

  public function __construct(MTInfoturRepository $InfoturRepository, MTInfotur $Infotur, Traza $traza)
  {
    $this->InfoturRepository = $InfoturRepository;
    $this->Infotur = $Infotur;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTInfotur
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $data = $this->InfoturRepository->listInfotur_();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Infotur->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Infotur->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTInfotur whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $data = $this->InfoturRepository->listInfotur_paginate($input);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Infotur->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Infotur->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTInfotur in storage.
   *
   * @param CreateMTInfoturAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTInfoturAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'infotur' => 'required|unique:mtinfoturs'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Infotur->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->InfoturRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Infotur->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Infotur->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Infotur->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTInfotur.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTInfotur $data */
    $data = $this->InfoturRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($data->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTInfotur in storage.
   *
   * @param int $id
   * @param UpdateMTInfoturAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTInfoturAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'infotur' => 'required|unique:mtinfoturs,infotur,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Infotur->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTInfotur $data */
      $objActualizar = $data = $this->InfoturRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data = $this->InfoturRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Infotur->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Infotur->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Infotur->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTInfotur from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTInfotur $data */
      $data = $this->InfoturRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Infotur->model, json_encode($data));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Infotur->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

