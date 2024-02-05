<?php

namespace App\Http\Controllers\API;

use App\Models\MTSistGestionOperadora;
use App\Repositories\MTSistGestionOperadoraRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

class MTSistGestionOperadoraAPIController extends AppBaseController
{
  /** @var  MTSistGestionOperadoraRepository */
  private $sistGestionOperadoraRepository;

  /** @var  MTSistGestionOperadora */
  private $sistGestionOperadora;

  public function __construct(MTSistGestionOperadoraRepository $sistGestionOperadoraRepo, MTSistGestionOperadora $sistGestionOperadora)
  {
    $this->sistGestionOperadoraRepository = $sistGestionOperadoraRepo;
    $this->sistGestionOperadora = $sistGestionOperadora;
  }

  /**
   * Display a listing of the MTSistGestionOperadora.
   * GET|HEAD /operadoras
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    $operadoras = $this->sistGestionOperadoraRepository->all(
      $request->except(['skip', 'limit']),
      $request->get('skip'),
      $request->get('limit')
    );

    return $this->sendResponse($operadoras->toArray(), 'Operadoras retrieved successfully');
  }

  /**
   * Store a newly created MTSistGestionOperadora in storage.
   * POST /operadoras
   *
   * @param CreateSistGestionOperadoraAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateSistGestionOperadoraAPIRequest $request)
  {
    $input = $request->all();

    $operadoras = $this->sistGestionOperadoraRepository->create($input);

    return $this->sendResponse($operadoras->toArray(), 'Operadoras saved successfully');
  }

  public function getOperadoras(Request $request)
  {
    try {
      $operadoras = $this->sistGestionOperadoraRepository->listOperadoras();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionOperadora->model]));
      return $this->sendResponse($operadoras->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionOperadora->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionOperadora->model]), $exception->getMessage(), '500');
    }
  }

  public function addOperadora(Request $request)
  {
    try {
      $input = $request->all();
      $operadora = $this->sistGestionOperadoraRepository->create($input);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionOperadora->model]));
      return $this->sendResponse($operadora->toArray(), 'Operadora saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionOperadora->model]), $exception->getMessage(), '500');
    }
  }

  public function editOperadora(Request $request)
  {
    try {
      $input = $request->all();
      $id=$request['id'];
      $operadora = $this->sistGestionOperadoraRepository->update($input,$id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionOperadora->model]));
      return $this->sendResponse($operadora->toArray(), 'Operadora saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionOperadora->model]), $exception->getMessage(), '500');
    }
  }

  public function deleteOperadora($id)
  {
    try {
      $operadora = $this->sistGestionOperadoraRepository->delete($id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionOperadora->model]));
      return $this->sendResponse($operadora, 'Operadora saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionOperadora->model]), $exception->getMessage(), '500');
    }
  }
}
