<?php

namespace App\Http\Controllers;

use App\Models\MTPatogenoIdentificado;
use App\Repositories\MTPatogenoIdentificadoRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTPatogenoIdentificadoController extends AppBaseController
{
  private $mtPatogenoIdentificadoRepository;
  private $mtPatogenoIdentificado;
  private $traza;

  public function __construct(MTPatogenoIdentificadoRepository $mtPatogenoIdentificadoRepository, MTPatogenoIdentificado $mtPatogenoIdentificado, Traza $traza)
  {
    $this->mtPatogenoIdentificadoRepository = $mtPatogenoIdentificadoRepository;
    $this->mtPatogenoIdentificado = $mtPatogenoIdentificado;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtPatogenoIdentificadoRepository->listMTPatogenoIdentificado();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'patogeno_identificado' => 'required|unique:mtpatogeno_identificado',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtPatogenoIdentificado->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtPatogenoIdentificado = $this->mtPatogenoIdentificadoRepository->createMTPatogenoIdentificado($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtPatogenoIdentificado->model, json_encode($mtPatogenoIdentificado));
    if (!$mtPatogenoIdentificado)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtPatogenoIdentificado, 'msg' => 'Patogeno identificado creado']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'patogeno_identificado' => 'required|unique:mtpatogeno_identificado,patogeno_identificado,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtPatogenoIdentificado->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtPatogenoIdentificadoRepository->updateMTPatogenoIdentificado($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtPatogenoIdentificado->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Patogeno identificado actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtPatogenoIdentificado->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtPatogenoIdentificadoRepository->eliminarMTPatogenoIdentificado($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtPatogenoIdentificado->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtPatogenoIdentificado->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtPatogenoIdentificado->model]), $exception->getMessage(), '500');
    }
  }

}
