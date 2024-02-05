<?php

namespace App\Http\Controllers;

use App\Models\MTTipoVTuristicos;
use App\Repositories\MTTipoVTuristicosRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoVTuristicosController extends AppBaseController
{
  private $mtTipoVTuristicosRepository;
  private $mtTipoVTuristicos;
  private $traza;

  public function __construct(MTTipoVTuristicosRepository $mtTipoVTuristicosRepository, MTTipoVTuristicos $mtTipoVTuristicos, Traza $traza)
  {
    $this->mtTipoVTuristicosRepository = $mtTipoVTuristicosRepository;
    $this->mtTipoVTuristicos = $mtTipoVTuristicos;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoVTuristicosRepository->listMTTipoVTuristicos();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipovturisticos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoVTuristicos->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoVTuristicos = $this->mtTipoVTuristicosRepository->createMTTipoVTuristicos($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoVTuristicos->model, json_encode($mtTipoVTuristicos));
    if (!$mtTipoVTuristicos)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoVTuristicos, 'msg' => 'Tipo de vehiculo turístico']);
  }

  public function get(Request $request)
  {
    try {
      $tipovturisticos = $this->mtTipoVTuristicosRepository->getMTTipoVTuristicos();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoVTuristicos->model]));
      return $this->sendResponse($tipovturisticos->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoVTuristicos->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoVTuristicos->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required|unique:mttipovturisticos,tipo,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoVTuristicos->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoVTuristicosRepository->updateMTTipoVTuristicos($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoVTuristicos->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de vehículo turistico actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoVTuristicos->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoVTuristicosRepository->eliminarMTTipoVTuristicos($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoVTuristicos->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoVTuristicos->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoVTuristicos->model]), $exception->getMessage(), '500');
    }
  }

}
