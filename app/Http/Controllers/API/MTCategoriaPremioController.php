<?php

namespace App\Http\Controllers\API;

use App\Models\MTCategoriaPremio;
use App\Repositories\MTCategoriaPremioRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;

class MTCategoriaPremioController extends AppBaseController
{
  private $mtCategoriaPremioRepository;
  private $mtCategoriaPremio;
  private $traza;

  public function __construct(MTCategoriaPremioRepository $mtCategoriaPremioRepository, MTCategoriaPremio $mtCategoriaPremio, Traza $traza)
  {
    $this->mtCategoriaPremioRepository = $mtCategoriaPremioRepository;
    $this->mtCategoriaPremio = $mtCategoriaPremio;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtCategoriaPremioRepository->listMTCategoriaPremio();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtcategoriapremio'
    ]);

    if ($validate->fails())
    {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtCategoriaPremio->model, 'operation' => 'creada']), '500');
    }

    /** @var User $user */
    $mtCategoriaPremio = $this->mtCategoriaPremioRepository->create($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtCategoriaPremio->model, json_encode($mtCategoriaPremio));
    if (!$mtCategoriaPremio)
      return response()->json(['error' => true, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtCategoriaPremio, 'msg' => 'Categoría creada']);
  }

  public function get(Request $request)
  {
    try {
      $categoria_premio = $this->mtCategoriaPremioRepository->getCategoria_premios();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtCategoriaPremio->model]));
      return $this->sendResponse($categoria_premio->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtCategoriaPremio->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCategoriaPremio->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtcategoriapremio,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtCategoriaPremio->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtCategoriaPremioRepository->updateMTCategoriaPremio($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtCategoriaPremio->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Categoria de premio actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCategoriaPremio->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtCategoriaPremioRepository->eliminarMTCategoriaPremio($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtCategoriaPremio->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtCategoriaPremio->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtCategoriaPremio->model]), $exception->getMessage(), '500');
    }
  }
}
