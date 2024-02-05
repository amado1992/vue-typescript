<?php

namespace App\Http\Controllers;

use App\Models\MTSituacionAMTransporte;
use App\Repositories\MTSituacionAMTransporteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTSituacionAMTransporteController extends AppBaseController
{
  private $mtSituacionAMTransporteRepository;
  private $mtSituacionAMTransporte;
  private $traza;

  public function __construct(MTSituacionAMTransporteRepository $mtSituacionAMTransporteRepository, MTSituacionAMTransporte $mtSituacionAMTransporte, Traza $traza)
  {
    $this->mtSituacionAMTransporteRepository = $mtSituacionAMTransporteRepository;
    $this->mtSituacionAMTransporte = $mtSituacionAMTransporte;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtSituacionAMTransporteRepository->listMTSituacionAMTransporte();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'situacion_actual' => 'required|unique:mtsituacionamtransportes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtSituacionAMTransporte->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtSituacionAMTransporte = $this->mtSituacionAMTransporteRepository->createMTSituacionAMTransporte($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtSituacionAMTransporte->model, json_encode($mtSituacionAMTransporte));
    if (!$mtSituacionAMTransporte)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtSituacionAMTransporte, 'msg' => 'Situacion actual de medio de transporte creada']);
  }

  public function get(Request $request)
  {
    try {
      $SituacionAMTransporte = $this->mtSituacionAMTransporteRepository->getMTSituacionAMTransporte();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtSituacionAMTransporte->model]));
      return $this->sendResponse($SituacionAMTransporte->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtSituacionAMTransporte->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtSituacionAMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'situacion_actual' => 'required|unique:mtsituacionamtransportes,situacion_actual,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtSituacionAMTransporte->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtSituacionAMTransporteRepository->updateMTSituacionAMTransporte($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtSituacionAMTransporte->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Situación actual de medios de transporte OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtSituacionAMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtSituacionAMTransporteRepository->eliminarMTSituacionAMTransporte($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtSituacionAMTransporte->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtSituacionAMTransporte->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtSituacionAMTransporte->model]), $exception->getMessage(), '500');
    }
  }

}
