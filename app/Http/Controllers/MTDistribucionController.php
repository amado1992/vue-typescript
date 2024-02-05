<?php

namespace App\Http\Controllers;

use App\Models\MTDistribucion;
use App\Models\Traza;
use App\Repositories\MTDistribucionRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTDistribucionController extends AppBaseController
{
  private $mtDistribucionRepository;
  private $mtDistribucion;
  private $traza;

  public function __construct(MTDistribucionRepository $mtDistribucionRepository, MTDistribucion $mtDistribucion, Traza $traza)
  {
    $this->mtDistribucionRepository = $mtDistribucionRepository;
    $this->mtDistribucion = $mtDistribucion;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtDistribucionRepository->listMTDistribucion();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtdistribucion',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtDistribucion->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtDistribucion = $this->mtDistribucionRepository->createMTDistribucion($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtDistribucion->model, json_encode($mtDistribucion));
    if (!$mtDistribucion)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => null, 'msg' => 'Distribucion creada']);
  }

  public function get(Request $request)
  {
    try {
      $distribucion = $this->mtDistribucionRepository->getMTDistribuciones();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtDistribucion->model]));
      return $this->sendResponse($distribucion->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtDistribucion->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtDistribucion->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtdistribucion,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtDistribucion->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtDistribucionRepository->updateMTDistribucion($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtDistribucion->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Distribución de almacen actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtDistribucion->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtDistribucionRepository->eliminarMTDistribucion($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtDistribucion->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtDistribucion->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtDistribucion->model]), $exception->getMessage(), '500');
    }
  }

}
