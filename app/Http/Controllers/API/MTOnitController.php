<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTOnitAPIRequest;
use App\Http\Requests\API\UpdateMTOnitAPIRequest;
use App\Models\MTOnit;
use App\Models\Traza;
use App\Repositories\MTOnitRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTOnitController
 * @package App\Http\Controllers\API
 */
class MTOnitController extends AppBaseController
{
  /** @var  MTOnitRepository */
  private $mtOnitRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTOnit */
  private $Onit;

  public function __construct(MTOnitRepository $mtOnitRepository, MTOnit $Onit, Traza $traza)
  {
    $this->mtOnitRepository = $mtOnitRepository;
    $this->traza = $traza;
    $this->Onit = $Onit;
  }

  /**
   * Display a listing of the Onit.
   * GET|HEAD /onit
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $data = $this->mtOnitRepository->getAllPaginateOnit($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->Onit->model]));
      return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->Onit->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Onit->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Onit in storage.
   * POST /onit
   *
   * @param CreateMTOnitAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTOnitAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtonits',
      'nombre' => 'required|unique:mtonits'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->Onit->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->mtOnitRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->Onit->model, 'operation' => 'creada']) . ' con id = ' . $data->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Onit->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Onit->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Onit->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Onit.
   * GET|HEAD /onit/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTOnit $data */
    $data = $this->mtOnitRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Elemento no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Elemento obtenido satisfactoriamente');
  }

  /**
   * Update the specified Onit in storage.
   * PUT/PATCH /onit/{id}
   *
   * @param int $id
   * @param UpdateMTOnitAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTOnitAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtonits,codigo,' .$id,
      'nombre' => 'required|unique:mtonits,nombre,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o ese codigo');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->Onit->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();

      /** @var MTOnit $data */
      $objActualizar = $data = $this->mtOnitRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data = $this->mtOnitRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Onit->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Onit->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Onit->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Onit from storage.
   * DELETE /onit/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTOnit $data */
      $data = $this->mtOnitRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->Onit->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Onit->model, json_encode($data));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Onit->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTOnit $data */
    $data = $this->mtOnitRepository->listMTOnit();

    if (empty($data)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Data obtenida satisfactoriamente');
  }
}
