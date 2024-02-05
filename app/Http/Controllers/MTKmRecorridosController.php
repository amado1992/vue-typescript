<?php

namespace App\Http\Controllers;

use App\Models\MTKmRecorridos;
use App\Repositories\MTKmRecorridosRepository;
use App\Repositories\UserRepository;
use App\Http\Resources\MTKmRecorridosResource;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificarAsignacionKmRecorridos;

class MTKmRecorridosController extends AppBaseController
{
  private $mtKmRecorridosRepository;
  private $mtKmRecorridos;
  private $traza;
  private $userRepository;

  public function __construct(MTKmRecorridosRepository $mtKmRecorridosRepository, MTKmRecorridos $mtKmRecorridos, Traza $traza, UserRepository $userRepository)
  {
    $this->mtKmRecorridosRepository = $mtKmRecorridosRepository;
    $this->mtKmRecorridos = $mtKmRecorridos;
    $this->traza = $traza;
    $this->userRepository = $userRepository;
  }

  public function index()
  {
    $data = $this->mtKmRecorridosRepository->listMTKmRecorridos();
    return $data;
  }

  public function store(Request $request)
  {
    $validate = validator($request->all(), [
      'mes' => 'required',
      'anno' => 'required',
      'km_recorridos' => 'required',
      'vehiculo_empresa_id' => 'required'
    ]);

    if ($validate->fails())
      redirect()->back()->withInput()->withErrors($validate->errors());
    /** @var User $user */
    $mtKmRecorridos = $this->mtKmRecorridosRepository->createMTKmRecorridos($request->all());
    $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtKmRecorridos->model, json_encode($mtKmRecorridos));
    if (!$mtKmRecorridos)
      return response()->json(['error' => false, 'data' => null, 'msg' => 'Creación fallida']);
    return response()->json(['success' => true, 'data' => $mtKmRecorridos, 'msg' => 'KmRecorridos creado']);
  }

  public function show($id)
  {
    $data = MTKmRecorridos::findOrFail($id);
    return new MTKmRecorridosResource($data);
  }

  public function update(Request $request, $id)
  {
    try {
      $data = $this->mtKmRecorridosRepository->updateMTKmRecorridos($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtKmRecorridos->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'KmRecorridos actualizado OK']);
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtKmRecorridos->model]), $exception->getMessage(), '500');
    }
  }
}
