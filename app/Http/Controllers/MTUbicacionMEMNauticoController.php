<?php

namespace App\Http\Controllers;

use App\Models\MTUbicacionMEMNautico;
use App\Repositories\MTUbicacionMEMNauticoRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTUbicacionMEMNauticoController extends AppBaseController
{
  private $mtUbicacionMEMNauticoRepository;
  private $mtUbicacionMEMNautico;
  private $traza;

  public function __construct(MTUbicacionMEMNauticoRepository $mtUbicacionMEMNauticoRepository, MTUbicacionMEMNautico $mtUbicacionMEMNautico, Traza $traza)
  {
    $this->mtUbicacionMEMNauticoRepository = $mtUbicacionMEMNauticoRepository;
    $this->mtUbicacionMEMNautico = $mtUbicacionMEMNautico;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtUbicacionMEMNauticoRepository->listMTUbicacionMEMNautico();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'ubicacion' => 'required|unique:mtubicacionmemnauticos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtUbicacionMEMNautico->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtUbicacionMEMNautico = $this->mtUbicacionMEMNauticoRepository->createMTUbicacionMEMNautico($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtUbicacionMEMNautico->model, json_encode($mtUbicacionMEMNautico));
    if (!$mtUbicacionMEMNautico)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtUbicacionMEMNautico, 'msg' => 'Ubicacion de motor de embarcación y servicio náutico creada']);
  }

  public function get(Request $request)
  {
    try {
      $UbicacionMEMNautico = $this->mtUbicacionMEMNauticoRepository->getMTUbicacionMEMNautico();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtUbicacionMEMNautico->model]));
      return $this->sendResponse($UbicacionMEMNautico->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtUbicacionMEMNautico->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtUbicacionMEMNautico->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'ubicacion' => 'required|unique:mtubicacionmemnauticos,ubicacion,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtUbicacionMEMNautico->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtUbicacionMEMNauticoRepository->updateMTUbicacionMEMNautico($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtUbicacionMEMNautico->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Ubicacion actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtUbicacionMEMNautico->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtUbicacionMEMNauticoRepository->eliminarMTUbicacionMEMNautico($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtUbicacionMEMNautico->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtUbicacionMEMNautico->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtUbicacionMEMNautico->model]), $exception->getMessage(), '500');
    }
  }

}
