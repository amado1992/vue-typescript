<?php

namespace App\Http\Controllers;

use App\Models\MTClasificacionEvento;
use App\Repositories\MTClasificacionEventoRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTClasificacionEventoController extends AppBaseController
{
  private $mtClasificacionEventoRepository;
  private $mtClasificacionEvento;
  private $traza;

  public function __construct(MTClasificacionEventoRepository $mtClasificacionEventoRepository, MTClasificacionEvento $mtClasificacionEvento, Traza $traza)
  {
    $this->mtClasificacionEventoRepository = $mtClasificacionEventoRepository;
    $this->mtClasificacionEvento = $mtClasificacionEvento;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtClasificacionEventoRepository->listMTClasificacionEvento();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'clasificacion_evento' => 'required|unique:mtclasificacion_eventos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtClasificacionEvento->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtClasificacionEvento = $this->mtClasificacionEventoRepository->createMTClasificacionEvento($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtClasificacionEvento->model, json_encode($mtClasificacionEvento));
    if (!$mtClasificacionEvento)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtClasificacionEvento, 'msg' => 'Clasificacion evento creada']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'clasificacion_evento' => 'required|unique:mtclasificacion_eventos,clasificacion_evento,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtClasificacionEvento->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtClasificacionEventoRepository->updateMTClasificacionEvento($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtClasificacionEvento->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Clasificacion evento actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtClasificacionEvento->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtClasificacionEventoRepository->eliminarMTClasificacionEvento($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtClasificacionEvento->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtClasificacionEvento->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtClasificacionEvento->model]), $exception->getMessage(), '500');
    }
  }

}
