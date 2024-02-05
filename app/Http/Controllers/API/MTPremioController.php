<?php

namespace App\Http\Controllers\API;

use App\Models\MTPremio;
use App\Repositories\MTPremioRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class MTPremioController extends AppBaseController
{
  private $mtPremioRepository;
  private $mtPremio;
  private $traza;

  public function __construct(MTPremioRepository $mtPremioRepository, MTPremio $mtPremio, Traza $traza)
  {
    $this->mtPremioRepository = $mtPremioRepository;
    $this->mtPremio = $mtPremio;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtPremioRepository->listMTPremio();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtpremio'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtPremio->model, 'operation' => 'creada']), '500');
    }

    try {
      $user = Auth::user();
      $mtPremio = $this->mtPremioRepository->createMTPremio($request->all(), $user->instalacion_id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtPremio->model, json_encode($mtPremio));
      return $this->sendResponse($mtPremio, __('messages.app.model_success', ['model' => $this->mtPremio->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtPremio->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtpremio,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtPremio->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtPremioRepository->updateMTPremio($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtPremio->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Premio actualizado OK']);
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtPremioRepository->eliminarMTPremio($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtPremio->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtPremio->model, json_encode($data));

      return $data;
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }

  public function cantPremiosOsde()
  {
    try {
      $data = $this->mtPremioRepository->cantPremiosOsde();
      return $data;
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }

  public function cantPremiosCategoriaOsde()
  {
    try {
      $data = $this->mtPremioRepository->cantPremiosCategoriaOsde();
      return $data;
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }

  public function porcientoPremiosEntidad()
  {
    try {
      $data = $this->mtPremioRepository->porcientoPremiosEntidad();
      return $data;
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }

  /** Dashboard */
  public function cantPremiosCAtegoriaDashboard(Request $request)
  {
    try {
      $indicador = $this->mtPremioRepository->getCantPremiosXcategoria($request);
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->mtPremio->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }
  public function cantPremios5yearsDashboard(Request $request)
  {
    try {
      $indicador = $this->mtPremioRepository->getCantPremios5years($request);
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->mtPremio->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPremio->model]), $exception->getMessage(), '500');
    }
  }
}
