<?php

namespace App\Http\Controllers;

use App\Models\MTTipoVEspecializados;
use App\Repositories\MTTipoVEspecializadosRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoVEspecializadosController extends AppBaseController
{
  private $mtTipoVEspecializadosRepository;
  private $mtTipoVEspecializados;
  private $traza;

  public function __construct(MTTipoVEspecializadosRepository $mtTipoVEspecializadosRepository, MTTipoVEspecializados $mtTipoVEspecializados, Traza $traza)
  {
    $this->mtTipoVEspecializadosRepository = $mtTipoVEspecializadosRepository;
    $this->mtTipoVEspecializados = $mtTipoVEspecializados;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoVEspecializadosRepository->listMTTipoVEspecializados();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipovespecializados',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoVEspecializados->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoVEspecializados = $this->mtTipoVEspecializadosRepository->createMTTipoVEspecializados($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoVEspecializados->model, json_encode($mtTipoVEspecializados));
    if (!$mtTipoVEspecializados)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoVEspecializados, 'msg' => 'Tipo de vehiculo especializado']);
  }

  public function get(Request $request)
  {
    try {
      $tipovespecializados = $this->mtTipoVEspecializadosRepository->getMTTipoVEspecializados();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoVEspecializados->model]));
      return $this->sendResponse($tipovespecializados->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoVEspecializados->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoVEspecializados->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipovespecializados,tipo,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoVEspecializados->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoVEspecializadosRepository->updateMTTipoVEspecializados($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoVEspecializados->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de vehículo especializado actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoVEspecializados->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoVEspecializadosRepository->eliminarMTTipoVEspecializados($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoVEspecializados->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoVEspecializados->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoVEspecializados->model]), $exception->getMessage(), '500');
    }
  }

}
