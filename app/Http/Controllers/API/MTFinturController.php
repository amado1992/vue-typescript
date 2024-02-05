<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTFinturAPIRequest;
use App\Http\Requests\API\UpdateMTFinturAPIRequest;
use App\Models\MTFintur;
use App\Models\Traza;
use App\Repositories\MTFinturRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTFinturController
 * @package App\Http\Controllers\API
 */
class MTFinturController extends AppBaseController
{
  /** @var  MTFinturRepository */
  private $mtFinturRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTFintur */
  private $Fintur;

  public function __construct(MTFinturRepository $mtFinturRepository, MTFintur $Fintur, Traza $traza)
  {
    $this->mtFinturRepository = $mtFinturRepository;
    $this->traza = $traza;
    $this->Fintur = $Fintur;
  }

  /**
   * Display a listing of the Fintur.
   * GET|HEAD /Fintur
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $data = $this->mtFinturRepository->getAllPaginateFintur($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->Fintur->model]));
      return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->Fintur->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->Fintur->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Fintur in storage.
   * POST /Fintur
   *
   * @param CreateMTFinturAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTFinturAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtfinturs',
      'nombre' => 'required|unique:mtfinturs'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->Fintur->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->mtFinturRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->Fintur->model, 'operation' => 'creada']) . ' con id = ' . $data->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->Fintur->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Fintur->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Fintur->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Fintur.
   * GET|HEAD /Fintur/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTFintur $data */
    $data = $this->mtFinturRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Elemento no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Elemento obtenido satisfactoriamente');
  }

  /**
   * Update the specified Fintur in storage.
   * PUT/PATCH /Fintur/{id}
   *
   * @param int $id
   * @param UpdateMTFinturAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTFinturAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtfinturs,codigo,' .$id,
      'nombre' => 'required|unique:mtfinturs,nombre,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o ese codigo');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->Fintur->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();

      /** @var MTFintur $data */
      $objActualizar = $data = $this->mtFinturRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data = $this->mtFinturRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->Fintur->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->Fintur->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Fintur->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Fintur from storage.
   * DELETE /Fintur/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTFintur $data */
      $data = $this->mtFinturRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->Fintur->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->Fintur->model, json_encode($data));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->Fintur->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTFintur $data */
    $data = $this->mtFinturRepository->listMTFintur();

    if (empty($data)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Data obtenida satisfactoriamente');
  }
}
