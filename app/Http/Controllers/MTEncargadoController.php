<?php

namespace App\Http\Controllers;

use App\Models\MTEncargado;
use App\Models\Traza;
use App\Repositories\MTEncargadoRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTEncargadoController extends AppBaseController
{
  private $mtEncargadoRepository;
  private $mtEncargado;
  private $traza;

  public function __construct(MTEncargadoRepository $mtEncargadoRepository, MTEncargado $mtEncargado, Traza $traza)
  {
    $this->mtEncargadoRepository = $mtEncargadoRepository;
    $this->mtEncargado = $mtEncargado;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtEncargadoRepository->listMTEncargado();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtencargado',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtEncargado->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtEncargado = $this->mtEncargadoRepository->createMTEncargado($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtEncargado->model, json_encode($mtEncargado));
    if (!$mtEncargado) return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtEncargado, 'msg' => 'Encargado creado']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:mtencargado,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtEncargado->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtEncargadoRepository->updateMTEncargado($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtEncargado->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Encargado actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtEncargado->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtEncargadoRepository->eliminarMTEncargado($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtEncargado->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtEncargado->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtEncargado->model]), $exception->getMessage(), '500');
    }
  }
}
