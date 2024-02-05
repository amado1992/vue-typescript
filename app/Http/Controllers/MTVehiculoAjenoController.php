<?php

namespace App\Http\Controllers;

use App\Models\MTVehiculoAjeno;
use App\Repositories\MTVehiculoAjenoRepository;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MTVehiculoAjenoController extends AppBaseController
{
  private $mtVehiculoAjenoRepository;
  private $mtVehiculoAjeno;
  private $traza;

  public function __construct(MTVehiculoAjenoRepository $mtVehiculoAjenoRepository, MTVehiculoAjeno $mtVehiculoAjeno, Traza $traza)
  {
    $this->mtVehiculoAjenoRepository = $mtVehiculoAjenoRepository;
    $this->mtVehiculoAjeno = $mtVehiculoAjeno;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtVehiculoAjenoRepository->listMTVehiculoAjeno();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'tipo' => 'required',
      'marca' => 'required',
      'matricula' => 'required',
    ]);

    if ($validate->fails())
      redirect()->back()->withInput()->withErrors($validate->errors());
    /** @var User $user */
    $mtVehiculoAjeno = $this->mtVehiculoAjenoRepository->createMTVehiculoAjeno($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtVehiculoAjeno->model, json_encode($mtVehiculoAjeno));
    if (!$mtVehiculoAjeno)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' =>'$mtVehiculoAjeno', 'msg' => 'Vehiculo ajeno creado']);
  }

  public function get(Request $request)
  {
    try {
      $VehiculoAjeno = $this->mtVehiculoAjenoRepository->getMTVehiculoAjeno();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->mtVehiculoAjeno->model]));
      return $this->sendResponse($VehiculoAjeno->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtVehiculoAjeno->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtVehiculoAjeno->model]), $exception->getMessage(), '500');
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $data = $this->mtVehiculoAjenoRepository->updateMTVehiculoAjeno($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtVehiculoAjeno->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Vehiculo ajeno actualizado OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtVehiculoAjeno->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtVehiculoAjenoRepository->eliminarMTVehiculoAjeno($id, $request);
      Log::info(__('messages.app.model_success', ['model' => $this->mtVehiculoAjeno->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtVehiculoAjeno->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtVehiculoAjeno->model]), $exception->getMessage(), '500');
    }
  }

}
