<?php

namespace App\Http\Controllers;

use App\Models\MTClasificacionAccidente;
use App\Repositories\MTClasificacionAccidenteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTClasificacionAccidenteController extends AppBaseController
{
  private $mtClasificacionAccidenteRepository;
  private $mtClasificacionAccidente;
  private $traza;

  public function __construct(MTClasificacionAccidenteRepository $mtClasificacionAccidenteRepository, MTClasificacionAccidente $mtClasificacionAccidente, Traza $traza)
  {
    $this->mtClasificacionAccidenteRepository = $mtClasificacionAccidenteRepository;
    $this->mtClasificacionAccidente = $mtClasificacionAccidente;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtClasificacionAccidenteRepository->listMTClasificacionAccidente();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'clasificacion_accidente' => 'required|unique:mtclasificacionaccidentes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtClasificacionAccidente->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtClasificacionAccidente = $this->mtClasificacionAccidenteRepository->createMTClasificacionAccidente($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtClasificacionAccidente->model, json_encode($mtClasificacionAccidente));
    if (!$mtClasificacionAccidente)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtClasificacionAccidente, 'msg' => 'Clasificacion de accidente creado']);
  }

  public function get(Request $request)
  {
    try {
      $clasificacionaccidente = $this->mtClasificacionAccidenteRepository->getMTClasificacionAccidente();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtClasificacionAccidente->model]));
      return $this->sendResponse($clasificacionaccidente->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtClasificacionAccidente->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtClasificacionAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'clasificacion_accidente' => 'required|unique:mtclasificacionaccidentes,clasificacion_accidente,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtClasificacionAccidente->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtClasificacionAccidenteRepository->updateMTClasificacionAccidente($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtClasificacionAccidente->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Clasificacion de accidente actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtClasificacionAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtClasificacionAccidenteRepository->eliminarMTClasificacionAccidente($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtClasificacionAccidente->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtClasificacionAccidente->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtClasificacionAccidente->model]), $exception->getMessage(), '500');
    }
  }

}
