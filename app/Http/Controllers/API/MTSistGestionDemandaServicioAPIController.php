<?php

namespace App\Http\Controllers\API;

use App\Models\MTSistGestionDemandaServicio;
use App\Repositories\MTSistGestionDemandaServicioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

class MTSistGestionDemandaServicioAPIController extends AppBaseController
{
  /** @var  MTSistGestionDemandaServicioRepository */
  private $sistGestionDemandaServicioRepository;

  /** @var  MTSistGestionDemandaServicio */
  private $sistGestionDemandaServicio;

  public function __construct(MTSistGestionDemandaServicioRepository $sistGestionDemandaServicioRepo, MTSistGestionDemandaServicio $sistGestionDemandaServicio)
  {
    $this->sistGestionDemandaServicioRepository = $sistGestionDemandaServicioRepo;
    $this->sistGestionDemandaServicio = $sistGestionDemandaServicio;
  }

  /**
   * Display a listing of the MTSistGestionDemandaServicio.
   * GET|HEAD /demandaservicio
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    $demandaservicio = $this->sistGestionDemandaServicioRepository->all(
      $request->except(['skip', 'limit']),
      $request->get('skip'),
      $request->get('limit')
    );

    return $this->sendResponse($demandaservicio->toArray(), 'Demanda del Servicio retrieved successfully');
  }

  /**
   * Store a newly created MTSistGestionDemandaServicio in storage.
   * POST /demandaservicios
   *
   * @param CreateSistGestionDemandaServicioAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateSistGestionDemandaServicioAPIRequest $request)
  {
    $input = $request->all();

    $demandaservicio = $this->sistGestionDemandaServicioRepository->create($input);

    return $this->sendResponse($demandaservicio->toArray(), 'Demanda del Servicio saved successfully');
  }

  public function getDemandaServicio(Request $request)
  {
    try {
      $demandaservicio = $this->sistGestionDemandaServicioRepository->listDemandaServicios();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionDemandaServicio->model]));
      return $this->sendResponse($demandaservicio->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionDemandaServicio->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionDemandaServicio->model]), $exception->getMessage(), '500');
    }
  }

  public function addDemandaServicio(Request $request)
  {
    try {
      $input = $request->all();
      $demandaservicio = $this->sistGestionDemandaServicioRepository->create($input);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionDemandaServicio->model]));
      return $this->sendResponse($demandaservicio->toArray(), 'Demanda del Servicio saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionDemandaServicio->model]), $exception->getMessage(), '500');
    }
  }

  public function editDemandaServicio(Request $request)
  {
    try {
      $input = $request->all();
      $id=$request['id'];
      $demandaservicio = $this->sistGestionDemandaServicioRepository->update($input,$id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionDemandaServicio->model]));
      return $this->sendResponse($demandaservicio->toArray(), 'Demanda del Servicio saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionDemandaServicio->model]), $exception->getMessage(), '500');
    }
  }

  public function deleteDemandaServicio($id)
  {
    try {
      $demandaservicio = $this->sistGestionDemandaServicioRepository->delete($id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionDemandaServicio->model]));
      return $this->sendResponse($demandaservicio, 'Demanda del Servicio saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionDemandaServicio->model]), $exception->getMessage(), '500');
    }
  }
}
