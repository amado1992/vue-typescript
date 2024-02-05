<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTCategoriaInstalacionAPIRequest;
use App\Http\Requests\API\UpdateMTCategoriaInstalacionAPIRequest;
use App\Models\MTCategoriaInstalacion;
use App\Models\Traza;
use App\Repositories\MTCategoriaInstalacionRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTCategoriaInstalacionController
 * @package App\Http\Controllers\API
 */
class MTCategoriaInstalacionController extends AppBaseController
{
  /** @var  MTCategoriaInstalacionRepository */
  private $mtCategoriaInstalacionRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTCategoriaInstalacion */
  private $CategoriaInstalacion;

  public function __construct(MTCategoriaInstalacionRepository $mtCategoriaInstalacionRepository, MTCategoriaInstalacion $CategoriaInstalacion, Traza $traza)
  {
    $this->mtCategoriaInstalacionRepository = $mtCategoriaInstalacionRepository;
    $this->traza = $traza;
    $this->CategoriaInstalacion = $CategoriaInstalacion;
  }

  /**
   * Display a listing of the CategoriaInstalacion.
   * GET|HEAD /categoriainstalacion
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $data = $this->mtCategoriaInstalacionRepository->getAllPaginateCategoriaInstalacion($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->CategoriaInstalacion->model]));
      return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->CategoriaInstalacion->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->CategoriaInstalacion->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created CategoriaInstalacion in storage.
   * POST /categoriainstalacion
   *
   * @param CreateMTCategoriaInstalacionAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTCategoriaInstalacionAPIRequest $request)
  {
      $validate = validator($request->all(), [
        'categoria_instalacion' => 'required|unique:mtcategoria_instalacions'
      ]);

      if ($validate->fails())
      {
        Log::error('Existe un registro con ese nombre');
        return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'creada']), '500');
      }

    try {
      $input = $request->all();
      $data = $this->mtCategoriaInstalacionRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'creada']) . ' con id = ' . $data->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->CategoriaInstalacion->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified CategoriaInstalacion.
   * GET|HEAD /categoriainstalacion/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTCategoriaInstalacion $data */
    $data = $this->mtCategoriaInstalacionRepository->find($id);

    if (empty($data)) {
      return $this->sendError('CategoriaInstalacion no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'CategoriaInstalacion obtenido satisfactoriamente');
  }

  /**
   * Update the specified CategoriaInstalacion in storage.
   * PUT/PATCH /categoriainstalacion/{id}
   *
   * @param int $id
   * @param UpdateMTCategoriaInstalacionAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTCategoriaInstalacionAPIRequest $request)
  {
      $validate = validator($request->all(), [
        'categoria_instalacion' => 'required|unique:mtcategoria_instalacions,categoria_instalacion,' .$id
      ]);

      if ($validate->fails())
      {
        Log::error('Existe un registro con ese nombre');
        return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'creada']), '500');
      }

    try {
      $input = $request->all();

      /** @var MTCategoriaInstalacion $data */
      $objActualizar = $data = $this->mtCategoriaInstalacionRepository->find($id);

      if (empty($data)) {
        return $this->sendError('CategoriaInstalacion not found');
      }

      $data = $this->mtCategoriaInstalacionRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->CategoriaInstalacion->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified CategoriaInstalacion from storage.
   * DELETE /categoriainstalacion/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTCategoriaInstalacion $data */
      $data = $this->mtCategoriaInstalacionRepository->find($id);

      if (empty($data)) {
        return $this->sendError('CategoriaInstalacion not found');
      }

      $data->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->CategoriaInstalacion->model, json_encode($data));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->CategoriaInstalacion->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTCategoriaInstalacion $data */
    $data = $this->mtCategoriaInstalacionRepository->listMTCategoriaInstalacion();

    if (empty($data)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Data obtenida satisfactoriamente');
  }
}
