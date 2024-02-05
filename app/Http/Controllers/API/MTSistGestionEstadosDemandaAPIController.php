<?php

namespace App\Http\Controllers\API;

use App\Models\MTSistGestionEstadosDemanda;
use App\Repositories\MTSistGestionEstadoDemandaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MTSistGestionEstadosDemandaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

class MTSistGestionEstadosDemandaAPIController extends AppBaseController
{
  /** @var  MTSistGestionEstadoDemandaRepository */
  private $sistGestionEstadoDemandaRepository;

  /** @var  MTSistGestionEstadosDemanda */
  private $sistGestionEstadosDemanda;

  public function __construct(MTSistGestionEstadoDemandaRepository $sistGestionEstadoDemandaRepo, MTSistGestionEstadosDemanda $sistGestionEstadosDemanda)
  {
    $this->sistGestionEstadoDemandaRepository = $sistGestionEstadoDemandaRepo;
    $this->sistGestionEstadosDemanda = $sistGestionEstadosDemanda;
  }

  /**
   * Display a listing of the MTSistGestionEstadosDemanda.
   * GET|HEAD /estados
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    $estados = $this->sistGestionEstadoDemandaRepository->all(
      $request->except(['skip', 'limit']),
      $request->get('skip'),
      $request->get('limit')
    );

    return $this->sendResponse($estados->toArray(), 'Estados de demandas retrieved successfully');
  }

  /**
   * Store a newly created MTSistGestionEstadosDemanda in storage.
   * POST /estados
   *
   * @param CreateSistGestionEstadosDemandasAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateSistGestionEstadosDemandasAPIRequest $request)
  {
    $input = $request->all();

    $estados = $this->sistGestionEstadoDemandaRepository->create($input);

    return $this->sendResponse($estados->toArray(), 'Estados de demandas saved successfully');
  }

  public function getEstadosDemanda(Request $request)
  {
    try {
      $estados = $this->sistGestionEstadoDemandaRepository->listEstadosDemanda();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]));
      //$f=$this->sendResponse(new MTSistGestionEstadosDemandaResource($estados), 'M T Mes saved successfully');
      //dd($f);
      //print_r($estados);
      //print_r($f);
        //exit();
//      $result = $estados->transform(function ($item)
//      {
//        return [
//          'idDemand'=> $item->id_EstadoDemanda,
//          'descDemand'=> $item->desc_EstadoDemanda,
//
//        ];
//      });

      return $this->sendResponse($estados, 'M T Mes saved successfully');
      //return $this->sendResponse(new Resources\MTSistGestionEstadosDemandaResource($estados), 'MTSistGestionEstadosDemanda get successfully');
      //return $this->sendResponse($estados->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]), $exception->getMessage(), '500');
    }
  }

  public function addEstadoDemanda(Request $request)
  {
    try {
      $input = $request->all();
      $estados = $this->sistGestionEstadoDemandaRepository->create($input);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]));
      return $this->sendResponse($estados->toArray(), 'Estado de demanda saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]), $exception->getMessage(), '500');
    }
  }

  public function editEstadoDemanda(Request $request)
  {
    try {
      $input = $request->all();
      $id=$request['id'];
      $estados = $this->sistGestionEstadoDemandaRepository->update($input,$id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]));
      return $this->sendResponse($estados->toArray(), 'Estado de demanda saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]), $exception->getMessage(), '500');
    }
  }

  public function deleteEstadoDemanda($id)
  {
    try {
      $estados = $this->sistGestionEstadoDemandaRepository->delete($id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]));
      return $this->sendResponse($estados, 'Estado de demanda saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionEstadosDemanda->model]), $exception->getMessage(), '500');
    }
  }
}
