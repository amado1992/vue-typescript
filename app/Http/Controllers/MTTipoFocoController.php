<?php

namespace App\Http\Controllers;

use App\Models\MTTipoFoco;
use App\Repositories\MTTipoFocoRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoFocoController extends AppBaseController
{
  private $mtTipoFocoRepository;
  private $mtTipoFoco;
  private $traza;

  public function __construct(MTTipoFocoRepository $mtTipoFocoRepository, MTTipoFoco $mtTipoFoco, Traza $traza)
  {
    $this->mtTipoFocoRepository = $mtTipoFocoRepository;
    $this->mtTipoFoco = $mtTipoFoco;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoFocoRepository->listMTTipoFoco();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo_foco' => 'required|unique:mttipo_focos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoFoco->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoFoco = $this->mtTipoFocoRepository->createMTTipoFoco($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoFoco->model, json_encode($mtTipoFoco));
    if (!$mtTipoFoco)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoFoco, 'msg' => 'Tipo de foco creado']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo_foco' => 'required|unique:mttipo_focos,tipo_foco,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoFoco->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoFocoRepository->updateMTTipoFoco($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoFoco->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de foco actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoFoco->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoFocoRepository->eliminarMTTipoFoco($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoFoco->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoFoco->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoFoco->model]), $exception->getMessage(), '500');
    }
  }

}
