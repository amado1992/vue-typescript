<?php

namespace App\Http\Controllers;

use App\Models\MTAlmacen;
use App\Models\Traza;
use App\Repositories\MTAlmacenRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MTAlmacenController extends AppBaseController
{
  private $mtAlmacenRepository;
  private $mtAlmacen;
  private $traza;

  public function __construct(MTAlmacenRepository $mtAlmacenRepository, MTAlmacen $mtAlmacen, Traza $traza)
  {
    $this->mtAlmacenRepository = $mtAlmacenRepository;
    $this->mtAlmacen = $mtAlmacen;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtAlmacenRepository->listMTAlmacen();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtalmacen',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtAlmacen->model, 'operation' => 'creada']), '500');

    try {
      $user = Auth::user();
      $mtAlmacen = $this->mtAlmacenRepository->createMTAlmacen($request->all(), $user->instalacion_id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtAlmacen->model, json_encode($mtAlmacen));
      return $this->sendResponse($mtAlmacen, __('messages.app.model_success', ['model' => $this->mtAlmacen->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtAlmacen->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  public function get(Request $request)
  {
    try {
      $almacen = $this->mtAlmacenRepository->getAlmacenes();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtAlmacen->model]));
      return $this->sendResponse($almacen->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtAlmacen->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtalmacen,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtAlmacen->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtAlmacenRepository->updateMTAlmacen($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtAlmacen->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Almacen actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtAlmacenRepository->eliminarMTAlmacen($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtAlmacen->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtAlmacen->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function mostrar_ubicaciongeografica()
  {
    try {
      $data = $this->mtAlmacenRepository->mostrar_ubicaciongeografica();
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function mostrar_almacenescategoria()
  {
    try {
      $data = $this->mtAlmacenRepository->mostrar_almacenescategoria();
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function mostrar_encargadoscapacitados()
  {
    try {
      $data = $this->mtAlmacenRepository->mostrar_encargadoscapacitados();
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function mostrar_almacenesinversionmantenimiento()
  {
    try {
      $data = $this->mtAlmacenRepository->mostrar_almacenesinversionmantenimiento();
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function mostrar_almacenesestadoconstructivo()
  {
    try {
      $data = $this->mtAlmacenRepository->mostrar_almacenesestadoconstructivo();
      return $data;
//      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->mtAlmacen->model]));
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  //Dashboard
  public function almacenesXcategoria(Request $request)
  {
    try {
      $data = $this->mtAlmacenRepository->getAlmacenesXcategoria($request);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->mtAlmacen->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtAlmacen->model]), $exception->getMessage(), '500');
    }
  }
}
