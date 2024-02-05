<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateIndicadorLineamientoAPIRequest;
use App\Http\Requests\API\UpdateIndicadorLineamientoAPIRequest;
use App\Models\MtIndicadoresLineamiento;
use App\Models\Traza;
use App\Repositories\MTIndicadoresLineamientoRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;
use Illuminate\Support\Arr;

/**
 * Class MTIndicadoresLineamientoController
 * @package App\Http\Controllers\API
 */
class MTIndicadoresLineamientoController extends AppBaseController
{
  /** @var  MTIndicadoresLineamientoRepository */
  private $indicadoreslRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MtIndicadoresLineamiento */
  private $indicadoresl;

  public function __construct(MTIndicadoresLineamientoRepository $indicadoreslRepository, MtIndicadoresLineamiento $indicadoresl, Traza $traza)
  {
    $this->indicadoreslRepository = $indicadoreslRepository;
    $this->traza = $traza;
    $this->indicadoresl = $indicadoresl;
  }

  /**
   * Display a listing of the IndicadoresLineamiento with pagination.
   * GET|HEAD /lineamientos
   *
   * @param Request $request
   * @return Response
   */
  public function listPaginate(Request $request)
  {
    try {
      $indicador = $this->indicadoreslRepository->getAllPaginateIndicadores($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicadoresl->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Display a listing of the IndicadoresLineamiento.
   * GET|HEAD /lineamientos
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $user = $request->user();
      $input = $request->all();
      $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
      $indicador = $this->indicadoreslRepository->listMTIndicadorLineamiento($input);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicadoresl->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created IndicadoresLineamiento in storage.
   * POST /lineamientos
   *
   * @param CreateIndicadorLineamientoAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateIndicadorLineamientoAPIRequest $request)
  {
    try {
      $user = $request->user();
      $input = $request->all();
      $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
      $indicador = $this->indicadoreslRepository->create($input);
      Log::info(__('messages.app.model_success', ['model' => $this->indicadoresl->model, 'operation' => 'creada']) . ' con id = ' . $indicador->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->indicadoresl->model, json_encode($indicador));
      return $this->sendResponse($indicador->toArray(), __('messages.app.model_success', ['model' => $this->indicadoresl->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->indicadoresl->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified IndicadoresLineamiento.
   * GET|HEAD /lineamientos/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MtIndicadoresLineamiento $indicador */
    $indicador = $this->indicadoreslRepository->find($id);

    if (empty($indicador)) {
      return $this->sendError('Indicador no encontrado');
    }

    return $this->sendResponse($indicador->toArray(), 'Datos obtenidos satisfactoriamente');
  }

  /**
   * Update the specified IndicadoresLineamiento in storage.
   * PUT/PATCH /lineamientos/{id}
   *
   * @param int $id
   * @param UpdateIndicadorLineamientoAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateIndicadorLineamientoAPIRequest $request)
  {
    try {
      $input = $request->all();

      /** @var MtIndicadoresLineamiento $indicador */
      $objActualizar = $indicador = $this->indicadoreslRepository->find($id);

      if (empty($indicador)) {
        return $this->sendError('Indicador no encontrado');
      }

      $indicador = $this->indicadoreslRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $indicador];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->indicadoresl->model, json_encode($objArray));
      return $this->sendResponse($indicador->toArray(), __('messages.app.model_success', ['model' => $this->indicadoresl->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->indicadoresl->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified IndicadoresLineamiento from storage.
   * DELETE /lineamientos/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MtIndicadoresLineamiento $indicador */
      $indicador = $this->indicadoreslRepository->find($id);

      if (empty($indicador)) {
        return $this->sendError('Indicador no encontrado');
      }

      $indicador->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->indicadoresl->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->indicadoresl->model, json_encode($indicador));
      return $this->sendSuccess('Indicador eliminado satisfactoriamente');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->indicadoresl->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  // Reporte - Lic. y Avales
  public function countLicAvales(Request $request)
  {
    //return $this->indicadoreslRepository->getLicenciasAvales();
    try {
      $indicador = $this->indicadoreslRepository->getLicenciasAvales($request);
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicadoresl->model]), $exception->getMessage(), '500');
    }
  }

  // Reporte - Servitur
  public function listServitur(Request $request)
  {
    try {
      $indicador = $this->indicadoreslRepository->getListaServitur($request);
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicadoresl->model]), $exception->getMessage(), '500');
    }
  }

  // Reporte - Cubasol
  public function listCubasol(Request $request)
  {
    try {
      $indicador = $this->indicadoreslRepository->getListaCubasol($request);
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicadoresl->model]), $exception->getMessage(), '500');
    }
  }
  // Dashboard
  public function totalLicenciasXestadoYear(Request $request)
  {
    try {
      $data = $this->indicadoreslRepository->getTotalLicenciasXestadoYear($request);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->indicadoresl->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicadoresl->model]), $exception->getMessage(), '500');
    }
  }
}
