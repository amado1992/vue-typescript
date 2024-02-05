<?php

namespace App\Http\Controllers\API;

use App\Models\MTSistGestionTiposSistGestion;
use App\Repositories\MTSistGestionTiposSistGestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

class MTSistGestionTiposSistGestionAPIController extends AppBaseController
{
  /** @var  MTSistGestionTiposSistGestionRepository */
  private $sistGestionTiposSistGestionRepository;

  /** @var  MTSistGestionTiposSistGestion */
  private $sistGestionTiposSistGestion;

  public function __construct(MTSistGestionTiposSistGestionRepository $sistGestionTiposSistGestionRepo, MTSistGestionTiposSistGestion $sistGestionTiposSistGestion)
  {
    $this->sistGestionTiposSistGestionRepository = $sistGestionTiposSistGestionRepo;
    $this->sistGestionTiposSistGestion = $sistGestionTiposSistGestion;
  }

  /**
   * Display a listing of the MTSistGestionTiposSistGestion.
   * GET|HEAD /tipossist
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    $tipossist = $this->sistGestionTiposSistGestionRepository->all(
      $request->except(['skip', 'limit']),
      $request->get('skip'),
      $request->get('limit')
    );

    return $this->sendResponse($tipossist->toArray(), 'Tipos de sistemas de gestión retrieved successfully');
  }

  /**
   * Store a newly created MTSistGestionTiposSistGestion in storage.
   * POST /tipossist
   *
   * @param CreateSistGestionTiposSistGestionAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateSistGestionTiposSistGestionAPIRequest $request)
  {
    $input = $request->all();

    $tipossist = $this->sistGestionTiposSistGestionRepository->create($input);

    return $this->sendResponse($tipossist->toArray(), 'Tipos de sistemas de gestión saved successfully');
  }

  public function getTiposSistGestion(Request $request)
  {
    try {
      $tipossist = $this->sistGestionTiposSistGestionRepository->listTiposSistGestion();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]));
      return $this->sendResponse($tipossist->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]), $exception->getMessage(), '500');
    }
  }

  public function addTiposSistGestion(Request $request)
  {
    try {
      $input = $request->all();
      $tipossist = $this->sistGestionTiposSistGestionRepository->create($input);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]));
      return $this->sendResponse($tipossist->toArray(), 'Tipo de sistema de gestion saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]), $exception->getMessage(), '500');
    }
  }

  public function editTiposSistGestion(Request $request)
  {
    try {
      $input = $request->all();
      $id=$request['id'];
      $operadora = $this->sistGestionTiposSistGestionRepository->update($input,$id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]));
      return $this->sendResponse($operadora->toArray(), 'Operadora saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]), $exception->getMessage(), '500');
    }
  }

  public function deleteTiposSistGestion($id)
  {
    try {
      $operadora = $this->sistGestionTiposSistGestionRepository->delete($id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]));
      return $this->sendResponse($operadora, 'Operadora saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionTiposSistGestion->model]), $exception->getMessage(), '500');
    }
  }
}
