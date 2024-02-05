<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTOaceAPIRequest;
use App\Http\Requests\API\UpdateMTOaceAPIRequest;
use App\Models\MTOace;
use App\Models\Traza;
use App\Repositories\MTOaceRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTOaceController
 * @package App\Http\Controllers\API
 */
class MTOaceController extends AppBaseController
{
  /** @var  MTOaceRepository */
  private $mtOaceRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTOace */
  private $Oace;

  public function __construct(MTOaceRepository $mtOaceRepository, MTOace $Oace, Traza $traza)
  {
    $this->mtOaceRepository = $mtOaceRepository;
    $this->traza = $traza;
    $this->Oace = $Oace;
  }

  /**
   * Display a listing of the Oace.
   * GET|HEAD /oace
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $oace = $this->mtOaceRepository->getAllPaginateOace($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->Oace->model]));
      return $this->sendResponse($oace->toArray(), __('messages.app.data_retrieved', ['model' => $this->Oace->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Oace->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Oace in storage.
   * POST /oace
   *
   * @param CreateOaceAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTOaceAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'oace' => 'required|unique:mtoaces',
      'siglas' => 'required|unique:mtoaces'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o esas siglas', ['model' => $this->Oace->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $oace = $this->mtOaceRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->Oace->model, 'operation' => 'creada']) . ' con id = ' . $oace->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Oace->model, json_encode($oace));
      return $this->sendResponse($oace->toArray(), __('messages.app.model_success', ['model' => $this->Oace->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Oace->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Oace.
   * GET|HEAD /oace/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTOace $oace */
    $oace = $this->mtOaceRepository->find($id);

    if (empty($oace)) {
      return $this->sendError('Oace no encontrada');
    }

    return $this->sendResponse($oace->toArray(), 'Oace obtenido satisfactoriamente');
  }

  /**
   * Update the specified Oace in storage.
   * PUT/PATCH /oace/{id}
   *
   * @param int $id
   * @param UpdateMTOaceAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTOaceAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'oace' => 'required|unique:mtoaces,oace,' .$id,
      'siglas' => 'required|unique:mtoaces,siglas,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o esas siglas', ['model' => $this->Oace->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();

      /** @var MTOace $oace */
      $objActualizar = $oace = $this->mtOaceRepository->find($id);

      if (empty($oace)) {
        return $this->sendError('Oace not found');
      }

      $oace = $this->mtOaceRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $oace];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Oace->model, json_encode($objArray));
      return $this->sendResponse($oace->toArray(), __('messages.app.model_success', ['model' => $this->Oace->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Oace->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Oace from storage.
   * DELETE /oace/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTOace $oace */
      $oace = $this->mtOaceRepository->find($id);

      if (empty($oace)) {
        return $this->sendError('Oace not found');
      }

      $oace->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->Oace->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Oace->model, json_encode($oace));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Oace->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTOace $oace */
    $oace = $this->mtOaceRepository->listMTTipoCaso();

    if (empty($oace)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($oace->toArray(), 'Data obtenida satisfactoriamente');
  }
}
