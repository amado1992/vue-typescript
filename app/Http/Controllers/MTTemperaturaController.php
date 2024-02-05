<?php

namespace App\Http\Controllers;

use App\Models\MTTemperatura;
use App\Models\Traza;
use App\Repositories\MTTemperaturaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTemperaturaController extends AppBaseController
{
  private $mtTemperaturaRepository;
  private $mtTemperatura;
  private $traza;

  public function __construct(MTTemperaturaRepository $mtTemperaturaRepository, MTTemperatura $mtTemperatura, Traza $traza)
  {
    $this->mtTemperaturaRepository = $mtTemperaturaRepository;
    $this->mtTemperatura = $mtTemperatura;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTemperaturaRepository->listMTTemperatura();
    return $data;
  }


  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mttemperatura',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTemperatura->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTemperatura = $this->mtTemperaturaRepository->createMTTemperatura($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTemperatura->model, json_encode($mtTemperatura));
    if (!$mtTemperatura)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTemperatura, 'msg' => 'Temperatura creada']);
  }

  public function get(Request $request)
  {
    try {
      $temperatura = $this->mtTemperaturaRepository->getMTTemperaturas();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTemperatura->model]));
      return $this->sendResponse($temperatura->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTemperatura->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTemperatura->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mttemperatura,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTemperatura->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTemperaturaRepository->updateMTTemperatura($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTemperatura->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Temperatura actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTemperatura->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTemperaturaRepository->eliminarMTTemperatura($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTemperatura->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTemperatura->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTemperatura->model]), $exception->getMessage(), '500');
    }
  }

}
