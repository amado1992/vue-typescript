<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFamiliaAPIRequest;
use App\Http\Requests\API\UpdateFamiliaAPIRequest;
use App\Models\Familia;
use App\Models\Traza;
use App\Repositories\FamiliaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class FamiliaController
 * @package App\Http\Controllers\API
 */
class FamiliaAPIController extends AppBaseController
{
  /** @var  FamiliaRepository */
  private $familiaRepository;

  /** @var  Familia */
  private $familia;

  /** @var  Traza */
  private $traza;

  public function __construct(FamiliaRepository $familiaRepo, Familia $familia, Traza $traza)
  {
    $this->familiaRepository = $familiaRepo;
    $this->traza = $traza;
    $this->familia = $familia;
  }

  /**
   * Display a listing of the Familia.
   * GET|HEAD /familias
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $familias = $this->familiaRepository->paginate($request['itemsPerPage'], '*', $request['page'], $request['search']);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->familia->model]));
      return $this->sendResponse($familias->toArray(), __('messages.app.data_retrieved', ['model' => $this->familia->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->familia->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Familia in storage.
   * POST /familias
   *
   * @param CreateFamiliaAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateFamiliaAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'nombre_familia' => 'required|unique:familia'
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->familia->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();
      $familia = $this->familiaRepository->create($input);
      Log::info(__('messages.app.model_success', ['model' => $this->familia->model, 'operation' => 'creada']) . ' con id = ' . $familia->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->familia->model, json_encode($familia));
      return $this->sendResponse($familia->toArray(), __('messages.app.model_success', ['model' => $this->familia->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->familia->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Familia.
   * GET|HEAD /familias/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var Familia $familia */
    $familia = $this->familiaRepository->find($id);

    if (empty($familia)) {
      return $this->sendError('Familia not found');
    }

    return $this->sendResponse($familia->toArray(), 'Familia retrieved successfully');
  }

  /**
   * Update the specified Familia in storage.
   * PUT/PATCH /familias/{id}
   *
   * @param int $id
   * @param UpdateFamiliaAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateFamiliaAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'nombre_familia' => 'required|unique:familia,nombre_familia,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->familia->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var Familia $familia */
      $objActualizar = $familia = $this->familia->find($id);

      if (empty($familia)) {
        return $this->sendError('Familia not found');
      }

      $familia = $this->familiaRepository->update($input, $id);

      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $familia];
      Log::info(__('messages.app.model_success', ['model' => $this->familia->model, 'operation' => 'actualizada']) . ' con id = ' . $familia->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->familia->model, json_encode($objArray));
      return $this->sendResponse($familia->toArray(), __('messages.app.model_success', ['model' => $this->familia->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->familia->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Familia from storage.
   * DELETE /familias/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var Familia $familia */
      $familia = $this->familiaRepository->find($id);

      if (empty($familia)) {
        return $this->sendError('Familia not found');
      }

      $familia->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->familia->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->familia->model, json_encode($familia));
      return $this->sendSuccess('Persona deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->familia->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function getFamilias(Request $request)
  {
    try {
      $familias = $this->familiaRepository->getFamilias();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->familia->model]));
      return $this->sendResponse($familias->toArray(), __('messages.app.data_retrieved', ['model' => $this->familia->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->familia->model]), $exception->getMessage(), '500');
    }
  }
}
