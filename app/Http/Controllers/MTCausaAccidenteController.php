<?php

namespace App\Http\Controllers;

use App\Models\MTCausaAccidente;
use App\Repositories\MTCausaAccidenteRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTCausaAccidenteController extends AppBaseController
{
  private $mtCausaAccidenteRepository;
  private $mtCausaAccidente;
  private $traza;

  public function __construct(MTCausaAccidenteRepository $mtCausaAccidenteRepository, MTCausaAccidente $mtCausaAccidente, Traza $traza)
  {
    $this->mtCausaAccidenteRepository = $mtCausaAccidenteRepository;
    $this->mtCausaAccidente = $mtCausaAccidente;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtCausaAccidenteRepository->listMTCausaAccidente();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'causa_accidente' => 'required|unique:mtcausaaccidentes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtCausaAccidente->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtCausaAccidente = $this->mtCausaAccidenteRepository->createMTCausaAccidente($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtCausaAccidente->model, json_encode($mtCausaAccidente));
    if (!$mtCausaAccidente)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtCausaAccidente, 'msg' => 'Causa de accidente creada']);
  }

  public function get(Request $request)
  {
    try {
      $causaaccidente = $this->mtCausaAccidenteRepository->getMTCausaAccidente();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtCausaAccidente->model]));
      return $this->sendResponse($causaaccidente->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtCausaAccidente->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCausaAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'causa_accidente' => 'required|unique:mtcausaaccidentes,causa_accidente,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtCausaAccidente->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtCausaAccidenteRepository->updateMTCausaAccidente($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtCausaAccidente->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Causa de accidente actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCausaAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtCausaAccidenteRepository->eliminarMTCausaAccidente($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtCausaAccidente->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtCausaAccidente->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtCausaAccidente->model]), $exception->getMessage(), '500');
    }
  }

}
