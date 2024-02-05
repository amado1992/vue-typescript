<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateVehiculoTransmisionAPIRequest;
use App\Http\Requests\API\UpdateVehiculoTransmisionAPIRequest;
use App\Models\MTVehiculoTransmision;
use App\Models\Traza;
use App\Repositories\MTVehiculoTransmisionRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTVehiculoTransmisionController
 * @package App\Http\Controllers\API
 */
class MTVehiculoTransmisionController extends AppBaseController
{
  /** @var  MTVehiculoTransmisionRepository */
  private $mtVehiculoTransmisionRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTVehiculoTransmision */
  private $VehiculoTransmision;

  public function __construct(MTVehiculoTransmisionRepository $mtVehiculoTransmisionRepository, MTVehiculoTransmision $VehiculoTransmision, Traza $traza)
  {
    $this->mtVehiculoTransmisionRepository = $mtVehiculoTransmisionRepository;
    $this->traza = $traza;
    $this->VehiculoTransmision = $VehiculoTransmision;
  }

  /**
   * Display a listing of the VehiculoTransmision.
   * GET|HEAD /vehiculotransmision
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $vehiculo = $this->mtVehiculoTransmisionRepository->getAllPaginateVehiculo($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->VehiculoTransmision->model]));
      return $this->sendResponse($vehiculo->toArray(), __('messages.app.data_retrieved', ['model' => $this->VehiculoTransmision->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->VehiculoTransmision->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created VehiculoTransmision in storage.
   * POST /vehiculotransmision
   *
   * @param CreateVehiculoTransmisionAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtvehiculo_transmisions')->count();
    $last_vehiculotransmision = MTVehiculoTransmision::all()->last();
    if (empty($last_vehiculotransmision))
      $cont = 1;
    else
      $cont = $arreglo + 1;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.$cont;
  }

  public function store(CreateVehiculoTransmisionAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'vehiculo' => 'required|unique:mtvehiculo_transmisions',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->VehiculoTransmision->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3); //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      $input = Arr::add($input, 'codigo', $codigo);

      $vehiculo = $this->mtVehiculoTransmisionRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->VehiculoTransmision->model, 'operation' => 'creada']) . ' con id = ' . $vehiculo->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->VehiculoTransmision->model, json_encode($vehiculo));
      return $this->sendResponse($vehiculo->toArray(), __('messages.app.model_success', ['model' => $this->VehiculoTransmision->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->VehiculoTransmision->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified VehiculoTransmision.
   * GET|HEAD /vehiculotransmision/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTVehiculoTransmision $vehiculotransmision */
    $vehiculotransmision = $this->mtVehiculoTransmisionRepository->find($id);

    if (empty($vehiculotransmision)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($vehiculotransmision->toArray(), 'Data obtenido satisfactoriamente');
  }

  /**
   * Update the specified VehiculoTransmision in storage.
   * PUT/PATCH /vehiculotransmision/{id}
   *
   * @param int $id
   * @param UpdateVehiculoTransmisionAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateVehiculoTransmisionAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'vehiculo' => 'required|unique:mtvehiculo_transmisions,vehiculo,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->VehiculoTransmision->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var MTVehiculoTransmision $vehiculotransmision */
      $objActualizar = $vehiculotransmision = $this->mtVehiculoTransmisionRepository->find($id);

      if (empty($vehiculotransmision)) {
        return $this->sendError('Data not found');
      }

      $vehiculotransmision = $this->mtVehiculoTransmisionRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $vehiculotransmision];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->VehiculoTransmision->model, json_encode($objArray));
      return $this->sendResponse($vehiculotransmision->toArray(), __('messages.app.model_success', ['model' => $this->VehiculoTransmision->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->VehiculoTransmision->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified VehiculoTransmision from storage.
   * DELETE /vehiculotransmision/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTVehiculoTransmision $vehiculotransmision */
      $vehiculotransmision = $this->mtVehiculoTransmisionRepository->find($id);

      if (empty($vehiculotransmision)) {
        return $this->sendError('Data not found');
      }

      $vehiculotransmision->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->VehiculoTransmision->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->VehiculoTransmision->model, json_encode($vehiculotransmision));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->VehiculoTransmision->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTVehiculoTransmision $vehiculotransmision */
    $vehiculotransmision = $this->mtVehiculoTransmisionRepository->listMTVehiculo();

    if (empty($vehiculotransmision)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($vehiculotransmision->toArray(), 'Data obtenida satisfactoriamente');
  }
}
