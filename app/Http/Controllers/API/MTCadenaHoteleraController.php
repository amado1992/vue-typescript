<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTCadenaHotelera;
use App\Models\Traza;
use App\Repositories\MTCadenaHoteleraRepository;
use App\Http\Requests\API\CreateMTCadenaHoteleraAPIRequest;
use App\Http\Requests\API\UpdateMTCadenaHoteleraAPIRequest;
use Illuminate\Support\Facades\Log;

class MTCadenaHoteleraController extends AppBaseController
{
  /** @var MTCadenaHoteleraRepository */
  private $CadenaHoteleraRepository;

  /** @var MTCadenaHotelera */
  private $CadenaHotelera;

  /** @var Traza */
  private $traza;

  public function __construct(MTCadenaHoteleraRepository $CadenaHoteleraRepository, MTCadenaHotelera $CadenaHotelera, Traza $traza)
  {
    $this->CadenaHoteleraRepository = $CadenaHoteleraRepository;
    $this->CadenaHotelera = $CadenaHotelera;
    $this->traza = $traza;
  }

  /**
   * Display a listing of the MTCadenaHotelera
   *
   * @param Request $request
   * @return Response
   */
  public function get()
  {
    try {
      $data = $this->CadenaHoteleraRepository->listMTCadenaHotelera();
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->CadenaHotelera->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->CadenaHotelera->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the MTCadenaHotelera whit pagination.
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $input = $request->all();
      $data = $this->CadenaHoteleraRepository->getAllPaginateCadenaHotelera($input);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->CadenaHotelera->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->CadenaHotelera->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MTCadenaHotelera in storage.
   *
   * @param CreateMTCadenaHoteleraAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTCadenaHoteleraAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'cadena_hotelera' => 'required|unique:mtcadena_hoteleras'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->CadenaHotelera->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->CadenaHoteleraRepository->create($input);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->CadenaHotelera->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->CadenaHotelera->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->CadenaHotelera->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MTCadenaHotelera.
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTCadenaHotelera $data */
    $data = $this->CadenaHoteleraRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Datos no encontrados');
    }

    return $this->sendResponse($data->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified MTCadenaHotelera in storage.
   *
   * @param int $id
   * @param UpdateMTCadenaHoteleraAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTCadenaHoteleraAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'cadena_hotelera' => 'required|unique:mtcadena_hoteleras,cadena_hotelera,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->CadenaHotelera->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->except('id');

      /** @var MTCadenaHotelera $data */
      $objActualizar = $data = $this->CadenaHoteleraRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data = $this->CadenaHoteleraRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->CadenaHotelera->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->CadenaHotelera->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->CadenaHotelera->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MTCadenaHotelera from storage.
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTCadenaHotelera $data */
      $data = $this->CadenaHoteleraRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Dato no encontrado');
      }

      $data->delete();

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->CadenaHotelera->model, json_encode($data));
      return $this->sendSuccess('Dato eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->CadenaHotelera->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}

