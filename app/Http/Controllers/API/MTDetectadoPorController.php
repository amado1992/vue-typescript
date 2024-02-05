<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDetectadoPorAPIRequest;
use App\Http\Requests\API\UpdateDetectadoPorAPIRequest;
use App\Models\MTDetectadoPor;
use App\Models\Traza;
use App\Repositories\MTDetectadoPorRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTDetectadoPorController
 * @package App\Http\Controllers\API
 */
class MTDetectadoPorController extends AppBaseController
{
  /** @var  MTDetectadoPorRepository */
  private $mtDetectadoPorRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTDetectadoPor */
  private $DetectadoPor;

  public function __construct(MTDetectadoPorRepository $mtDetectadoPorRepository, MTDetectadoPor $DetectadoPor, Traza $traza)
  {
    $this->mtDetectadoPorRepository = $mtDetectadoPorRepository;
    $this->traza = $traza;
    $this->DetectadoPor = $DetectadoPor;
  }

  /**
   * Display a listing of the DetectadoPor.
   * GET|HEAD /detectadopor
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $detectadopor = $this->mtDetectadoPorRepository->getAllPaginateDetectadoPor($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->DetectadoPor->model]));
      return $this->sendResponse($detectadopor->toArray(), __('messages.app.data_retrieved', ['model' => $this->DetectadoPor->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->DetectadoPor->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created DetectadoPor in storage.
   * POST /detectadopor
   *
   * @param CreateDetectadoPorAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtdetectado_por')->count();
    $last_detectado_por = MTDetectadoPor::all()->last();
    if (empty($last_detectado_por))
      $cont = 1;
    else
      $cont = $arreglo + 1;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.$cont;
  }

  public function store(CreateDetectadoPorAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'detectado_por' => 'required|unique:mtdetectado_por',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->DetectadoPor->model, 'operation' => 'creada']), '500');
    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3);
      $input = Arr::add($input, 'codigo', $codigo);

      $detectadopor = $this->mtDetectadoPorRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->DetectadoPor->model, 'operation' => 'creada']) . ' con id = ' . $detectadopor->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->DetectadoPor->model, json_encode($detectadopor));
      return $this->sendResponse($detectadopor->toArray(), __('messages.app.model_success', ['model' => $this->DetectadoPor->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->DetectadoPor->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified DetectadoPor.
   * GET|HEAD /detectadopor/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTDetectadoPor $detectadopor */
    $detectadopor = $this->mtDetectadoPorRepository->find($id);

    if (empty($detectadopor)) {
      return $this->sendError('Detectado por no encontrada');
    }

    return $this->sendResponse($detectadopor->toArray(), 'Detectado por obtenido satisfactoriamente');
  }

  /**
   * Update the specified DetectadoPor in storage.
   * PUT/PATCH /detectadopor/{id}
   *
   * @param int $id
   * @param UpdateDetectadoPorAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateDetectadoPorAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'detectado_por' => 'required|unique:mtdetectado_por,detectado_por,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->DetectadoPor->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var MTDetectadoPor $detectadopor */
      $objActualizar = $detectadopor = $this->mtDetectadoPorRepository->find($id);

      if (empty($detectadopor)) {
        return $this->sendError('DetectadoPor not found');
      }

      $detectadopor = $this->mtDetectadoPorRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $detectadopor];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->DetectadoPor->model, json_encode($objArray));
      return $this->sendResponse($detectadopor->toArray(), __('messages.app.model_success', ['model' => $this->DetectadoPor->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->DetectadoPor->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified DetectadoPor from storage.
   * DELETE /detectadopor/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTDetectadoPor $detectadopor */
      $detectadopor = $this->mtDetectadoPorRepository->find($id);

      if (empty($detectadopor)) {
        return $this->sendError('DetectadoPor not found');
      }

      $detectadopor->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->DetectadoPor->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->DetectadoPor->model, json_encode($detectadopor));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->DetectadoPor->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTDetectadoPor $detectadopor */
    $detectadopor = $this->mtDetectadoPorRepository->listMTDetectadoPor();

    if (empty($detectadopor)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($detectadopor->toArray(), 'Data obtenida satisfactoriamente');
  }
}
