<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTUnidadAdministrativaAPIRequest;
use App\Http\Requests\API\UpdateMTUnidadAdministrativaAPIRequest;
use App\Models\MTUnidadAdministrativa;
use App\Models\Traza;
use App\Repositories\MTUnidadAdministrativaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTUnidadAdministrativaController
 * @package App\Http\Controllers\API
 */
class MTUnidadAdministrativaController extends AppBaseController
{
  /** @var  MTUnidadAdministrativaRepository */
  private $mtUnidadAdministrativaRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTUnidadAdministrativa */
  private $UnidadAdministrativa;

  public function __construct(MTUnidadAdministrativaRepository $mtUnidadAdministrativaRepository, MTUnidadAdministrativa $UnidadAdministrativa, Traza $traza)
  {
    $this->mtUnidadAdministrativaRepository = $mtUnidadAdministrativaRepository;
    $this->traza = $traza;
    $this->UnidadAdministrativa = $UnidadAdministrativa;
  }

  /**
   * Display a listing of the UnidadAdministrativa.
   * GET|HEAD /UnidadAdministrativa
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $data = $this->mtUnidadAdministrativaRepository->getAllPaginateUnidadAdministrativa($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->UnidadAdministrativa->model]));
      return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->UnidadAdministrativa->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->UnidadAdministrativa->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created UnidadAdministrativa in storage.
   * POST /UnidadAdministrativa
   *
   * @param CreateMTUnidadAdministrativaAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTUnidadAdministrativaAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtunidad_administrativas',
      'nombre' => 'required|unique:mtunidad_administrativas'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->mtUnidadAdministrativaRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'creada']) . ' con id = ' . $data->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->UnidadAdministrativa->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified UnidadAdministrativa.
   * GET|HEAD /UnidadAdministrativa/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTUnidadAdministrativa $data */
    $data = $this->mtUnidadAdministrativaRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Elemento no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Elemento obtenido satisfactoriamente');
  }

  /**
   * Update the specified UnidadAdministrativa in storage.
   * PUT/PATCH /UnidadAdministrativa/{id}
   *
   * @param int $id
   * @param UpdateMTUnidadAdministrativaAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTUnidadAdministrativaAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtunidad_administrativas,codigo,' .$id,
      'nombre' => 'required|unique:mtunidad_administrativas,nombre,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o ese codigo');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();

      /** @var MTUnidadAdministrativa $data */
      $objActualizar = $data = $this->mtUnidadAdministrativaRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data = $this->mtUnidadAdministrativaRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->UnidadAdministrativa->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified UnidadAdministrativa from storage.
   * DELETE /UnidadAdministrativa/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTUnidadAdministrativa $data */
      $data = $this->mtUnidadAdministrativaRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->UnidadAdministrativa->model, json_encode($data));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->UnidadAdministrativa->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTUnidadAdministrativa $data */
    $data = $this->mtUnidadAdministrativaRepository->listMTUnidadAdministrativa();

    if (empty($data)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Data obtenida satisfactoriamente');
  }
}
