<?php

namespace App\Http\Controllers\API;

use App\Models\MTUnidadMedida;
use App\Repositories\MTUnidadMedidaRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;

class MTUnidadMedidaController extends AppBaseController
{
  private $mtUnidadMedidaRepository;
  private $mtUnidadMedida;
  private $traza;

  public function __construct(MTUnidadMedidaRepository $mtUnidadMedidaRepository, MTUnidadMedida $mtUnidadMedida, Traza $traza)
  {
    $this->mtUnidadMedidaRepository = $mtUnidadMedidaRepository;
    $this->mtUnidadMedida = $mtUnidadMedida;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtUnidadMedidaRepository->listMTUnidadMedida();
    return $data;
  }

  public function get(Request $request)
  {
    try {
      $unidad_medida = $this->mtUnidadMedidaRepository->getUnidadmedidas();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtUnidadMedida->model]));
      return $this->sendResponse($unidad_medida->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtUnidadMedida->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtUnidadMedida->model]), $exception->getMessage(), '500');
    }
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtunidadmedida'
    ]);

    if ($validate->fails()) {
      Log::error('Existe una unidad de medida con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtUnidadMedida->model, 'operation' => 'creada']), '500');
    }
    /** @var User $user */
    $mtUnidadMedida = $this->mtUnidadMedidaRepository->createMTUnidadMedida($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtUnidadMedida->model, json_encode($mtUnidadMedida));
    if (!$mtUnidadMedida)
      return response()->json(['error' => true, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtUnidadMedida, 'msg' => 'Unidad de medida creado']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtunidadmedida,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtUnidadMedida->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtUnidadMedidaRepository->updateMTUnidadMedida($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtUnidadMedida->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Almacen actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtUnidadMedida->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtUnidadMedidaRepository->eliminarMTUnidadMedida($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtUnidadMedida->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtUnidadMedida->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtUnidadMedida->model]), $exception->getMessage(), '500');
    }
  }

}
