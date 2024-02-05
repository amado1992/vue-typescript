<?php

namespace App\Http\Controllers\API;

use App\Models\MTProveedor;
use App\Repositories\MTProveedorRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;

class MTProveedorController extends AppBaseController
{
  private $mtProveedorRepository;
  private $mtProveedor;
  private $traza;

  public function __construct(MTProveedorRepository $mtProveedorRepository, MTProveedor $mtProveedor, Traza $traza)
  {
    $this->mtProveedorRepository = $mtProveedorRepository;
    $this->mtProveedor = $mtProveedor;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtProveedorRepository->listMTProveedor();
    return $data;
  }

  public function get(Request $request)
  {
    try {
      $proveedor = $this->mtProveedorRepository->getProveedores();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtProveedor->model]));
      return $this->sendResponse($proveedor->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtProveedor->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtProveedor->model]), $exception->getMessage(), '500');
    }
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'codigo' => 'required',
      'nombre' => 'required|unique:mtproveedor',
      'municipio_id' => 'required',
      'tipo_proveedor_id' => 'required'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un proveedor con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtProveedor->model, 'operation' => 'creada']), '500');
    }
    /** @var User $user */
    $mtProveedor = $this->mtProveedorRepository->createMTProveedor($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtProveedor->model, json_encode($mtProveedor));
    if (!$mtProveedor)
      return response()->json(['error' => true, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtProveedor, 'msg' => 'Proveedor creado']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtproveedor,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtProveedor->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtProveedorRepository->updateMTProveedor($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtProveedor->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Proveedor actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtProveedor->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtProveedorRepository->eliminarMTProveedor($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtProveedor->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtProveedor->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtProveedor->model]), $exception->getMessage(), '500');
    }
  }

}
