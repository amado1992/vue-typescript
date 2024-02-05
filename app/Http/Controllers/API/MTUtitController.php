<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTUtitAPIRequest;
use App\Http\Requests\API\UpdateMTUtitAPIRequest;
use App\Models\MTUtit;
use App\Models\Traza;
use App\Repositories\MTUtitRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTUtitController
 * @package App\Http\Controllers\API
 */
class MTUtitController extends AppBaseController
{
  /** @var  MTUtitRepository */
  private $mtUtitRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTUtit */
  private $Utit;

  public function __construct(MTUtitRepository $mtUtitRepository, MTUtit $Utit, Traza $traza)
  {
    $this->mtUtitRepository = $mtUtitRepository;
    $this->traza = $traza;
    $this->Utit = $Utit;
  }

  /**
   * Display a listing of the Utit.
   * GET|HEAD /Utit
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $data = $this->mtUtitRepository->getAllPaginateUtit($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->Utit->model]));
      return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->Utit->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Utit->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Utit in storage.
   * POST /Utit
   *
   * @param CreateMTUtitAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTUtitAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtutits',
      'nombre' => 'required|unique:mtutits'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->Utit->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->mtUtitRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->Utit->model, 'operation' => 'creada']) . ' con id = ' . $data->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Utit->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Utit->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Utit->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Utit.
   * GET|HEAD /Utit/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTUtit $data */
    $data = $this->mtUtitRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Elemento no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Elemento obtenido satisfactoriamente');
  }

  /**
   * Update the specified Utit in storage.
   * PUT/PATCH /Utit/{id}
   *
   * @param int $id
   * @param UpdateMTUtitAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTUtitAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtutits,codigo,' .$id,
      'nombre' => 'required|unique:mtutits,nombre,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o ese codigo');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->Utit->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();

      /** @var MTUtit $data */
      $objActualizar = $data = $this->mtUtitRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data = $this->mtUtitRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Utit->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Utit->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Utit->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Utit from storage.
   * DELETE /Utit/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTUtit $data */
      $data = $this->mtUtitRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->Utit->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Utit->model, json_encode($data));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Utit->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTUtit $data */
    $data = $this->mtUtitRepository->listMTUtit();

    if (empty($data)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Data obtenida satisfactoriamente');
  }
}
