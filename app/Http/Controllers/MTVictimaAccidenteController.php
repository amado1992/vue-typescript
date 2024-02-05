<?php

namespace App\Http\Controllers;

use App\Models\MTVictimaAccidente;
use App\Repositories\MTVictimaAccidenteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTVictimaAccidenteController extends AppBaseController
{
  private $mtVictimaAccidenteRepository;
  private $mtVictimaAccidente;
  private $traza;

  public function __construct(MTVictimaAccidenteRepository $mtVictimaAccidenteRepository, MTVictimaAccidente $mtVictimaAccidente, Traza $traza)
  {
    $this->mtVictimaAccidenteRepository = $mtVictimaAccidenteRepository;
    $this->mtVictimaAccidente = $mtVictimaAccidente;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtVictimaAccidenteRepository->listMTVictimaAccidente();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'victima_accidente' => 'required|unique:mtvictimaaccidentes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtVictimaAccidente->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtVictimaAccidente = $this->mtVictimaAccidenteRepository->createMTVictimaAccidente($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtVictimaAccidente->model, json_encode($mtVictimaAccidente));
    if (!$mtVictimaAccidente)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtVictimaAccidente, 'msg' => 'Victima de accidente']);
  }

  public function get(Request $request)
  {
    try {
      $victimaaccidente = $this->mtVictimaAccidenteRepository->getMTVictimaAccidente();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtVictimaAccidente->model]));
      return $this->sendResponse($victimaaccidente->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtVictimaAccidente->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtVictimaAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'victima_accidente' => 'required|unique:mtvictimaaccidentes,victima_accidente,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtVictimaAccidente->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtVictimaAccidenteRepository->updateMTVictimaAccidente($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtVictimaAccidente->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Victima de accidente actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtVictimaAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtVictimaAccidenteRepository->eliminarMTVictimaAccidente($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtVictimaAccidente->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtVictimaAccidente->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtVictimaAccidente->model]), $exception->getMessage(), '500');
    }
  }

}
