<?php

namespace App\Http\Controllers;

use App\Models\MTMotivoPMTransporte;
use App\Repositories\MTMotivoPMTransporteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTMotivoPMTransporteController extends AppBaseController
{
  private $mtMotivoPMTransporteRepository;
  private $mtMotivoPMTransporte;
  private $traza;

  public function __construct(MTMotivoPMTransporteRepository $mtMotivoPMTransporteRepository, MTMotivoPMTransporte $mtMotivoPMTransporte, Traza $traza)
  {
    $this->mtMotivoPMTransporteRepository = $mtMotivoPMTransporteRepository;
    $this->mtMotivoPMTransporte = $mtMotivoPMTransporte;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtMotivoPMTransporteRepository->listMTMotivoPMTransporte();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'motivo' => 'required|unique:mtmotivopmtransportes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtMotivoPMTransporte->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtMotivoPMTransporte = $this->mtMotivoPMTransporteRepository->createMTMotivoPMTransporte($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtMotivoPMTransporte->model, json_encode($mtMotivoPMTransporte));
    if (!$mtMotivoPMTransporte)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtMotivoPMTransporte, 'msg' => 'Motivo de paralisis de medio de transporte creada']);
  }

  public function get(Request $request)
  {
    try {
      $MotivoPMTransporte = $this->mtMotivoPMTransporteRepository->getMTMotivoPMTransporte();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtMotivoPMTransporte->model]));
      return $this->sendResponse($MotivoPMTransporte->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtMotivoPMTransporte->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtMotivoPMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'motivo' => 'required|unique:mtmotivopmtransportes,motivo,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtMotivoPMTransporte->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtMotivoPMTransporteRepository->updateMTMotivoPMTransporte($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtMotivoPMTransporte->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Motivo de parálisis de medio de transporte actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtMotivoPMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtMotivoPMTransporteRepository->eliminarMTMotivoPMTransporte($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtMotivoPMTransporte->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtMotivoPMTransporte->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtMotivoPMTransporte->model]), $exception->getMessage(), '500');
    }
  }

}
