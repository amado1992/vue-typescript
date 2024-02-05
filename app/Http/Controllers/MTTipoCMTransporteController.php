<?php

namespace App\Http\Controllers;

use App\Models\MTTipoCMTransporte;
use App\Repositories\MTTipoCMTransporteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoCMTransporteController extends AppBaseController
{
  private $mtTipoCMTransporteRepository;
  private $mtTipoCMTransporte;
  private $traza;

  public function __construct(MTTipoCMTransporteRepository $mtTipoCMTransporteRepository, MTTipoCMTransporte $mtTipoCMTransporte, Traza $traza)
  {
    $this->mtTipoCMTransporteRepository = $mtTipoCMTransporteRepository;
    $this->mtTipoCMTransporte = $mtTipoCMTransporte;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoCMTransporteRepository->listMTTipoCMTransporte();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'combustible' => 'required|unique:mttipocmtransportes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoCMTransporte->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoCMTransporte = $this->mtTipoCMTransporteRepository->createMTTipoCMTransporte($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoCMTransporte->model, json_encode($mtTipoCMTransporte));
    if (!$mtTipoCMTransporte)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoCMTransporte, 'msg' => 'Tipo de combustible de medio de transporte creada']);
  }

  public function get(Request $request)
  {
    try {
      $TipoCMTransporte = $this->mtTipoCMTransporteRepository->getMTTipoCMTransporte();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoCMTransporte->model]));
      return $this->sendResponse($TipoCMTransporte->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoCMTransporte->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoCMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'combustible' => 'required|unique:mttipocmtransportes,combustible,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoCMTransporte->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoCMTransporteRepository->updateMTTipoCMTransporte($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoCMTransporte->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de combustible de medios de transporte OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoCMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoCMTransporteRepository->eliminarMTTipoCMTransporte($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoCMTransporte->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoCMTransporte->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoCMTransporte->model]), $exception->getMessage(), '500');
    }
  }

}
