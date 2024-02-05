<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTEmpresa;
use App\Models\Traza;
use App\Repositories\MTEmpresaRepository;
use App\Http\Requests\API\CreateMTEmpresaAPIRequest;
use App\Http\Requests\API\UpdateMTEmpresaAPIRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;

class MTEmpresaController extends AppBaseController
{
  /** @var MTEmpresaRepository */
  private $EmpresaRepository;

  /** @var MTEmpresa */
  private $Empresa;

  /** @var Traza */
  private $traza;

  public function __construct(MTEmpresaRepository $EmpresaRepository, MTEmpresa $Empresa, Traza $traza)
  {
    $this->EmpresaRepository = $EmpresaRepository;
    $this->Empresa = $Empresa;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTEmpresa
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $data = $this->EmpresaRepository->listEmpresa_();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Empresa->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Empresa->model]), $exception->getMessage(), '500');
    }
  }

  public function getImportadora()
  {
    try {
      $data = $this->EmpresaRepository->listEmpresa_importadora();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Empresa->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Empresa->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTEmpresa  whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $data = $this->EmpresaRepository->listEmpresa_paginate($input);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->Empresa->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Empresa->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTEmpresa in storage.
   *
   * @param CreateMTEmpresaAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTEmpresaAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'empresa' => 'required|unique:mtempresas'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Empresa->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->EmpresaRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Empresa->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Empresa->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Empresa->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTEmpresa.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTEmpresa $data */
    $data = $this->EmpresaRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($data->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTEmpresa in storage.
   *
   * @param int $id
   * @param UpdateMTEmpresaAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTEmpresaAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'empresa' => 'required|unique:mtempresas,empresa,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->Empresa->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTEmpresa $data */
      $objActualizar = $data = $this->EmpresaRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data = $this->EmpresaRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Empresa->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Empresa->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Empresa->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTEmpresa from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTEmpresa $data */
      $data = $this->EmpresaRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Empresa->model, json_encode($data));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Empresa->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

