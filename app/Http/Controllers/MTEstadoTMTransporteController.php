<?php

namespace App\Http\Controllers;

use App\Models\MTEstadoTMTransporte;
use App\Repositories\MTEstadoTMTransporteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTEstadoTMTransporteController extends AppBaseController
{
  private $mtEstadoTMTransporteRepository;
  private $mtEstadoTMTransporte;
  private $traza;

  public function __construct(MTEstadoTMTransporteRepository $mtEstadoTMTransporteRepository, MTEstadoTMTransporte $mtEstadoTMTransporte, Traza $traza)
  {
    $this->mtEstadoTMTransporteRepository = $mtEstadoTMTransporteRepository;
    $this->mtEstadoTMTransporte = $mtEstadoTMTransporte;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtEstadoTMTransporteRepository->listMTEstadoTMTransporte();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'estado' => 'required|unique:mtestadotmtransportes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtEstadoTMTransporte->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtEstadoTMTransporte = $this->mtEstadoTMTransporteRepository->createMTEstadoTMTransporte($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtEstadoTMTransporte->model, json_encode($mtEstadoTMTransporte));
    if (!$mtEstadoTMTransporte)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtEstadoTMTransporte, 'msg' => 'Estado tecnico de medio de transporte creada']);
  }

  public function get(Request $request)
  {
    try {
      $estadotmtransporte = $this->mtEstadoTMTransporteRepository->getMTEstadoTMTransportes();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtEstadoTMTransporte->model]));
      return $this->sendResponse($estadotmtransporte->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtEstadoTMTransporte->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtEstadoTMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'estado' => 'required|unique:mtestadotmtransportes,estado,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtEstadoTMTransporte->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtEstadoTMTransporteRepository->updateMTEstadoTMTransporte($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtEstadoTMTransporte->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Estado tecnico de medio de transporte actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtEstadoTMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtEstadoTMTransporteRepository->eliminarMTEstadoTMTransporte($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtEstadoTMTransporte->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtEstadoTMTransporte->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtEstadoTMTransporte->model]), $exception->getMessage(), '500');
    }
  }

}
