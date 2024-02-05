<?php

namespace App\Http\Controllers\API;

use App\Models\MTTipoProveedor;
use App\Repositories\MTTipoProveedorRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;

class MTTipoProveedorController extends AppBaseController
{
  private $mtTipoProveedorRepository;
  private $mtTipoProveedor;
  private $traza;

  public function __construct(MTTipoProveedorRepository $mtTipoProveedorRepository, MTTipoProveedor $mtTipoProveedor, Traza $traza)
  {
    $this->mtTipoProveedorRepository = $mtTipoProveedorRepository;
    $this->mtTipoProveedor = $mtTipoProveedor;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoProveedorRepository->listMTTipoProveedor();
    return $data;
  }

  public function get(Request $request)
  {
    try {
      $tipo_proveedor = $this->mtTipoProveedorRepository->getTipo_proveedores();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtTipoProveedor->model]));
      return $this->sendResponse($tipo_proveedor->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtTipoProveedor->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoProveedor->model]), $exception->getMessage(), '500');
    }
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mttipoproveedor'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un tipo de proveedor con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoProveedor->model, 'operation' => 'creada']), '500');
    }
    /** @var User $user */
    $mtTipoProveedor = $this->mtTipoProveedorRepository->createMTTipoProveedor($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoProveedor->model, json_encode($mtTipoProveedor));
    if (!$mtTipoProveedor)
      return response()->json(['error' => true, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoProveedor, 'msg' => 'Tipo de proveedor creado']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mttipoproveedor,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoProveedor->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoProveedorRepository->updateMTTipoProveedor($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoProveedor->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de proveedor actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoProveedor->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoProveedorRepository->eliminarMTTipoProveedor($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoProveedor->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoProveedor->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoProveedor->model]), $exception->getMessage(), '500');
    }
  }

}
