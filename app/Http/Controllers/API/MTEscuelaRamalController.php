<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTEscuelaRamalAPIRequest;
use App\Http\Requests\API\UpdateMTEscuelaRamalAPIRequest;
use App\Models\MTEscuelaRamal;
use App\Models\Traza;
use App\Repositories\MTEscuelaRamalRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTEscuelaRamalController
 * @package App\Http\Controllers\API
 */
class MTEscuelaRamalController extends AppBaseController
{
  /** @var  MTEscuelaRamalRepository */
  private $mtEscuelaRamalRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTEscuelaRamal */
  private $EscuelaRamal;

  public function __construct(MTEscuelaRamalRepository $mtEscuelaRamalRepository, MTEscuelaRamal $EscuelaRamal, Traza $traza)
  {
    $this->mtEscuelaRamalRepository = $mtEscuelaRamalRepository;
    $this->traza = $traza;
    $this->EscuelaRamal = $EscuelaRamal;
  }

  /**
   * Display a listing of the EscuelaRamal.
   * GET|HEAD /EscuelaRamal
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $data = $this->mtEscuelaRamalRepository->getAllPaginateEscuelaRamal($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->EscuelaRamal->model]));
      return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->EscuelaRamal->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->EscuelaRamal->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created EscuelaRamal in storage.
   * POST /EscuelaRamal
   *
   * @param CreateMTEscuelaRamalAPIRequest $request
   *
   * @return Response
   */

  public function store(CreateMTEscuelaRamalAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtescuela_ramals',
      'nombre' => 'required|unique:mtescuela_ramals'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o esas siglas');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->EscuelaRamal->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $data = $this->mtEscuelaRamalRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->EscuelaRamal->model, 'operation' => 'creada']) . ' con id = ' . $data->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->EscuelaRamal->model, json_encode($data));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->EscuelaRamal->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->EscuelaRamal->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified EscuelaRamal.
   * GET|HEAD /EscuelaRamal/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTEscuelaRamal $data */
    $data = $this->mtEscuelaRamalRepository->find($id);

    if (empty($data)) {
      return $this->sendError('Elemento no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Elemento obtenido satisfactoriamente');
  }

  /**
   * Update the specified EscuelaRamal in storage.
   * PUT/PATCH /EscuelaRamal/{id}
   *
   * @param int $id
   * @param UpdateMTEscuelaRamalAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMTEscuelaRamalAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required|unique:mtescuela_ramals,codigo,' .$id,
      'nombre' => 'required|unique:mtescuela_ramals,nombre,' .$id
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre o ese codigo');
      return $this->sendError(__('Existe un registro con ese nombre o ese codigo', ['model' => $this->EscuelaRamal->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();

      /** @var MTEscuelaRamal $data */
      $objActualizar = $data = $this->mtEscuelaRamalRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data = $this->mtEscuelaRamalRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $data];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->EscuelaRamal->model, json_encode($objArray));
      return $this->sendResponse($data->toArray(), __('messages.app.model_success', ['model' => $this->EscuelaRamal->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->EscuelaRamal->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified EscuelaRamal from storage.
   * DELETE /EscuelaRamal/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTEscuelaRamal $data */
      $data = $this->mtEscuelaRamalRepository->find($id);

      if (empty($data)) {
        return $this->sendError('Element not found');
      }

      $data->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->EscuelaRamal->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->EscuelaRamal->model, json_encode($data));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->EscuelaRamal->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTEscuelaRamal $data */
    $data = $this->mtEscuelaRamalRepository->listMTEscuelaRamal();

    if (empty($data)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($data->toArray(), 'Data obtenida satisfactoriamente');
  }
}
