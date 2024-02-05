<?php

namespace App\Http\Controllers;

use App\Models\MTCategoria;
use App\Models\Traza;
use App\Repositories\MTCategoriaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTCategoriaController extends AppBaseController
{
  private $mtCategoriaRepository;
  private $mtCategoria;
  private $traza;

  public function __construct(MTCategoriaRepository $mtCategoriaRepository, MTCategoria $mtCategoria, Traza $traza)
  {
    $this->mtCategoriaRepository = $mtCategoriaRepository;
    $this->mtCategoria = $mtCategoria;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtCategoriaRepository->listMTCategoria();
    return $data;
  }


  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtcategoria',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtCategoria->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtCategoria = $this->mtCategoriaRepository->createMTCategoria($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtCategoria->model, json_encode($mtCategoria));
    if (!$mtCategoria)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtCategoria, 'msg' => 'Categoria creada']);
  }

  public function get(Request $request)
  {
    try {
      $categoria = $this->mtCategoriaRepository->getMTCategorias();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtCategoria->model]));
      return $this->sendResponse($categoria->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtCategoria->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCategoria->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtcategoria,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtCategoria->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtCategoriaRepository->updateMTCategoria($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtCategoria->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Categoría de almacen actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCategoria->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtCategoriaRepository->eliminarMTCategoria($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtCategoria->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtCategoria->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtCategoria->model]), $exception->getMessage(), '500');
    }
  }

}
