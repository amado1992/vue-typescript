<?php

namespace App\Http\Controllers;

use App\Models\MTTipoFlota;
use App\Repositories\MTTipoFlotaRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoFlotaController extends AppBaseController
{
  private $mtTipoFlotaRepository;
  private $mtTipoFlota;
  private $traza;

  public function __construct(MTTipoFlotaRepository $mtTipoFlotaRepository, MTTipoFlota $mtTipoFlota, Traza $traza)
  {
    $this->mtTipoFlotaRepository = $mtTipoFlotaRepository;
    $this->mtTipoFlota = $mtTipoFlota;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoFlotaRepository->listMTTipoFlota();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo_flota' => 'required|unique:mttipoflotas',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoFlota->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoFlota = $this->mtTipoFlotaRepository->createMTTipoFlota($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoFlota->model, json_encode($mtTipoFlota));
    if (!$mtTipoFlota)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoFlota, 'msg' => 'Tipo de flota creada']);
  }

  public function get(Request $request)
  {
    try {
      $tipoflota = $this->mtTipoFlotaRepository->getMTTipoFlotas();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoFlota->model]));
      return $this->sendResponse($tipoflota->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoFlota->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoFlota->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo_flota' => 'required|unique:mttipoflotas,tipo_flota,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoFlota->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoFlotaRepository->updateMTTipoFlota($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoFlota->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de flota actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoFlota->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoFlotaRepository->eliminarMTTipoFlota($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoFlota->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoFlota->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoFlota->model]), $exception->getMessage(), '500');
    }
  }

}
