<?php

namespace App\Http\Controllers;

use App\Models\MTTipoVAServicios;
use App\Repositories\MTTipoVAServiciosRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoVAServiciosController extends AppBaseController
{
  private $mtTipoVAServiciosRepository;
  private $mtTipoVAServicios;
  private $traza;

  public function __construct(MTTipoVAServiciosRepository $mtTipoVAServiciosRepository, MTTipoVAServicios $mtTipoVAServicios, Traza $traza)
  {
    $this->mtTipoVAServiciosRepository = $mtTipoVAServiciosRepository;
    $this->mtTipoVAServicios = $mtTipoVAServicios;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoVAServiciosRepository->listMTTipoVAServicios();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipovaservicios',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoVAServicios->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoVAServicios = $this->mtTipoVAServiciosRepository->createMTTipoVAServicios($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoVAServicios->model, json_encode($mtTipoVAServicios));
    if (!$mtTipoVAServicios)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoVAServicios, 'msg' => 'Tipo de vehiculo administrativo y de servicio creado']);
  }

  public function get(Request $request)
  {
    try {
      $tipovaservicios = $this->mtTipoVAServiciosRepository->getMTTipoVAServicios();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoVAServicios->model]));
      return $this->sendResponse($tipovaservicios->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoVAServicios->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoVAServicios->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipovaservicios,tipo,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoVAServicios->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoVAServiciosRepository->updateMTTipoVAServicios($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoVAServicios->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de vehículo administrativo y de servicio actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoVAServicios->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoVAServiciosRepository->eliminarMTTipoVAServicios($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoVAServicios->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoVAServicios->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoVAServicios->model]), $exception->getMessage(), '500');
    }
  }

}
