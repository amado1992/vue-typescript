<?php

namespace App\Http\Controllers;

use App\Models\MTDeteccion;
use App\Repositories\MTDeteccionRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTDeteccionController extends AppBaseController
{
  private $mtDeteccionRepository;
  private $mtDeteccion;
  private $traza;

  public function __construct(MTDeteccionRepository $mtDeteccionRepository, MTDeteccion $mtDeteccion, Traza $traza)
  {
    $this->mtDeteccionRepository = $mtDeteccionRepository;
    $this->mtDeteccion = $mtDeteccion;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtDeteccionRepository->listMTDeteccion();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'deteccion' => 'required|unique:mtdeteccion',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtDeteccion->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtDeteccion = $this->mtDeteccionRepository->createMTDeteccion($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtDeteccion->model, json_encode($mtDeteccion));
    if (!$mtDeteccion)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtDeteccion, 'msg' => 'Deteccion creada']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'deteccion' => 'required|unique:mtdeteccion,deteccion,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtDeteccion->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtDeteccionRepository->updateMTDeteccion($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtDeteccion->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Deteccion actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtDeteccion->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtDeteccionRepository->eliminarMTDeteccion($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtDeteccion->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtDeteccion->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtDeteccion->model]), $exception->getMessage(), '500');
    }
  }

}
