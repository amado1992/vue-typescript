<?php

namespace App\Http\Controllers;

use App\Models\MTTipoEMNauticos;
use App\Repositories\MTTipoEMNauticosRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoEMNauticosController extends AppBaseController
{
  private $mtTipoEMNauticosRepository;
  private $mtTipoEMNauticos;
  private $traza;

  public function __construct(MTTipoEMNauticosRepository $mtTipoEMNauticosRepository, MTTipoEMNauticos $mtTipoEMNauticos, Traza $traza)
  {
    $this->mtTipoEMNauticosRepository = $mtTipoEMNauticosRepository;
    $this->mtTipoEMNauticos = $mtTipoEMNauticos;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoEMNauticosRepository->listMTTipoEMNauticos();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipoemnauticos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoEMNauticos->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoEMNauticos = $this->mtTipoEMNauticosRepository->createMTTipoEMNauticos($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoEMNauticos->model, json_encode($mtTipoEMNauticos));
    if (!$mtTipoEMNauticos)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoEMNauticos, 'msg' => 'Tipo de embarcación y servicio náutico creada']);
  }

  public function get(Request $request)
  {
    try {
      $TipoEMNauticos = $this->mtTipoEMNauticosRepository->getMTTipoEMNauticos();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoEMNauticos->model]));
      return $this->sendResponse($TipoEMNauticos->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoEMNauticos->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoEMNauticos->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipoemnauticos,tipo,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoEMNauticos->model, 'operation' => 'creada']), '500');
    }
      try {
      $data = $this->mtTipoEMNauticosRepository->updateMTTipoEMNauticos($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoEMNauticos->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de embarcación y medios náuticos actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoEMNauticos->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoEMNauticosRepository->eliminarMTTipoEMNauticos($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoEMNauticos->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoEMNauticos->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoEMNauticos->model]), $exception->getMessage(), '500');
    }
  }

}
