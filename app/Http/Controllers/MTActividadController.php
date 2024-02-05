<?php

namespace App\Http\Controllers;

use App\Models\MTActividad;
use App\Models\Traza;
use App\Repositories\MTActividadRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTActividadController extends AppBaseController
{
  private $mtActividadRepository;
  private $mtActividad;
  private $traza;

  public function __construct(MTActividadRepository $mtActividadRepository, MTActividad $mtActividad, Traza $traza)
  {
    $this->mtActividadRepository = $mtActividadRepository;
    $this->mtActividad = $mtActividad;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtActividadRepository->listMTActividad();
    return $data;
  }


  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtactividad',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtActividad->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtActividad = $this->mtActividadRepository->createMTActividad($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtActividad->model, json_encode($mtActividad));
    if (!$mtActividad)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtActividad, 'msg' => 'Actividad creada']);
  }

  public function get(Request $request)
  {
    try {
      $actividad = $this->mtActividadRepository->getMTActividades();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtActividad->model]));
      return $this->sendResponse($actividad->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtActividad->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtActividad->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtactividad,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtActividad->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtActividadRepository->updateMTActividad($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtActividad->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Actividad de almacen actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtActividad->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtActividadRepository->eliminarMTActividad($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtActividad->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtActividad->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtActividad->model]), $exception->getMessage(), '500');
    }
  }

}
