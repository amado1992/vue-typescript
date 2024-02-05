<?php

namespace App\Http\Controllers;

use App\Models\MTClaseMTransporte;
use App\Repositories\MTClaseMTransporteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTClaseMTransporteController extends AppBaseController
{
  private $mtClaseMTransporteRepository;
  private $mtClaseMTransporte;
  private $traza;

  public function __construct(MTClaseMTransporteRepository $mtClaseMTransporteRepository, MTClaseMTransporte $mtClaseMTransporte, Traza $traza)
  {
    $this->mtClaseMTransporteRepository = $mtClaseMTransporteRepository;
    $this->mtClaseMTransporte = $mtClaseMTransporte;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtClaseMTransporteRepository->listMTClaseMTransporte();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'clase' => 'required|unique:mtclasemtransportes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtClaseMTransporte->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtClaseMTransporte = $this->mtClaseMTransporteRepository->createMTClaseMTransporte($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtClaseMTransporte->model, json_encode($mtClaseMTransporte));
    if (!$mtClaseMTransporte)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' =>'$mtClaseMTransporte', 'msg' => 'Clase de medio de transporte creada']);
  }

  public function get(Request $request)
  {
    try {
      $ClaseMTransporte = $this->mtClaseMTransporteRepository->getMTClaseMTransporte();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtClaseMTransporte->model]));
      return $this->sendResponse($ClaseMTransporte->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtClaseMTransporte->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtClaseMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'clase' => 'required|unique:mtclasemtransportes,clase,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtClaseMTransporte->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtClaseMTransporteRepository->updateMTClaseMTransporte($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtClaseMTransporte->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Clase de medio de transporte actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtClaseMTransporte->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtClaseMTransporteRepository->eliminarMTClaseMTransporte($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtClaseMTransporte->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtClaseMTransporte->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtClaseMTransporte->model]), $exception->getMessage(), '500');
    }
  }

}
