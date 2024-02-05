<?php

namespace App\Http\Controllers;

use App\Models\MTTipoMuestra;
use App\Repositories\MTTipoMuestraRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTTipoMuestraController extends AppBaseController
{
  private $mtTipoMuestraRepository;
  private $mtTipoMuestra;
  private $traza;

  public function __construct(MTTipoMuestraRepository $mtTipoMuestraRepository, MTTipoMuestra $mtTipoMuestra, Traza $traza)
  {
    $this->mtTipoMuestraRepository = $mtTipoMuestraRepository;
    $this->mtTipoMuestra = $mtTipoMuestra;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtTipoMuestraRepository->listMTTipoMuestra();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo_muestra' => 'required|unique:mttipo_muestras',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoMuestra->model, 'operation' => 'creada']), '500');

    /** @var User $user */
    $mtTipoMuestra = $this->mtTipoMuestraRepository->createMTTipoMuestra($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtTipoMuestra->model, json_encode($mtTipoMuestra));
    if (!$mtTipoMuestra)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtTipoMuestra, 'msg' => 'Tipo de muestra creada']);
  }

  public function update(Request $request, $id)
  {
    $validate = validator($request->all(), [
      'tipo_muestra' => 'required|unique:mttipo_muestras,tipo_muestra,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->mtTipoMuestra->model, 'operation' => 'creada']), '500');
    }
    try {
      $data = $this->mtTipoMuestraRepository->updateMTTipoMuestra($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtTipoMuestra->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Tipo de muestra actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtTipoMuestra->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtTipoMuestraRepository->eliminarMTTipoMuestra($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtTipoMuestra->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtTipoMuestra->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtTipoMuestra->model]), $exception->getMessage(), '500');
    }
  }

}
