<?php

namespace App\Http\Controllers;

use App\Models\MTTipoAlmacen;
use App\Models\Traza;
use App\Repositories\MTTipoAlmacenRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MTTipoAlmacenController extends AppBaseController
{
  private $mtTipoAlmacenRepository;
  private $mtTipoAlmacen;
  private $traza;

  public function __construct(MTTipoAlmacenRepository $mtTipoAlmacenRepository, MTTipoAlmacen $mtTipoAlmacen, Traza $traza)
  {
    $this->mtTipoAlmacenRepository = $mtTipoAlmacenRepository;
    $this->mtTipoAlmacen = $mtTipoAlmacen;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoAlmacenRepository->listMTTipoAlmacen();
    return $data;
  }

  public function store(Request $request)
  {
    try {
      $validate = validator($request->all(), [
        'nombre' => 'required|unique:mttipoalmacen',
      ]);

      if ($validate->fails())
        return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoAlmacen->model, 'operation' => 'creada']), '500');


      if (!isset($registros)) {
        $data = $this->mtTipoAlmacenRepository->createMTTipoAlmacen($request->all());
        $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoAlmacen->model, json_encode($data));
        return $this->sendResponse($data, __('messages.app.model_success', ['model' => $this->mtTipoAlmacen->model, 'operation' => 'creada']));
      }else
        return $this->sendError('El elemento ya existe', ['model' => $this->mtTipoAlmacen->model, 'operation' => 'creada'], 500);

      } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoAlmacen->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  public function get(Request $request)
  {
    try {
      $tipo_almacen = $this->mtTipoAlmacenRepository->getMTTipo_almacenes();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoAlmacen->model]));
      return $this->sendResponse($tipo_almacen->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoAlmacen->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mttipoalmacen,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoAlmacen->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoAlmacenRepository->updateMTTipoAlmacen($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoAlmacen->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de almacen actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoAlmacen->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoAlmacenRepository->eliminarMTTipoAlmacen($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoAlmacen->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoAlmacen->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoAlmacen->model]), $exception->getMessage(), '500');
    }
  }

}
