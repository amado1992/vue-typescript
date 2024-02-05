<?php

namespace App\Http\Controllers\API;

use App\Models\MTSistGestionRegistros;
use App\Repositories\MTSistGestionRegistrosRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

class MTSistGestionRegistrosAPIController extends AppBaseController
{
  /** @var  MTSistGestionRegistrosRepository */
  private $sistGestionRegistrosRepository;

  /** @var  MTSistGestionRegistros */
  private $sistGestionRegistros;

  public function __construct(MTSistGestionRegistrosRepository $sistGestionRegistrosRepo, MTSistGestionRegistros $sistGestionRegistros)
  {
    $this->sistGestionRegistrosRepository = $sistGestionRegistrosRepo;
    $this->sistGestionRegistros = $sistGestionRegistros;
  }

  /**
   * Display a listing of the MTSistGestionRegistros.
   * GET|HEAD /resgistros
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    $registros = $this->sistGestionRegistrosRepository->all(
      $request->except(['skip', 'limit']),
      $request->get('skip'),
      $request->get('limit')
    );

    return $this->sendResponse($registros->toArray(), 'Resgistros retrieved successfully');
  }

  /**
   * Store a newly created MTSistGestionRegistros in storage.
   * POST /resgistros
   *
   * @param CreateSistGestionRegistrosAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateSistGestionRegistrosAPIRequest $request)
  {
    $input = $request->all();

    $registros = $this->sistGestionRegistrosRepository->create($input);

    return $this->sendResponse($registros->toArray(), 'Resgistros saved successfully');
  }

  public function getAllRegistros(Request $request)
  {
    try {
      $input = $request->all();
      $id = $input['instalacion_id'];
      $registros = $this->sistGestionRegistrosRepository->listRegistros($id);
      $result = $registros->transform(function ($item)
      {
        return [
          'instalacion_name'=> $item->mtinstalacions->nombre,
          'osde_name'=> $item->mtinstalacions->entidades->osde->nombre,
          'id'=> $item->id,
          'tiposg_id'=> $item->mttipossistgests->id,
          'tiposg_desc'=> $item->mttipossistgests->desc_TipoSistGestion,
          'estado'=> $item->estado,
        ];
      });
//      return $this->sendResponse($registros, 'ok');

      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
      return $this->sendResponse($result->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionRegistros->model]), $exception->getMessage(), '500');
    }
  }

  public function getAllRegistersDemandas(Request $request)
  {
    try {

      $id = $request['osde_id'];
      $registro = $this->sistGestionRegistrosRepository->getRegistersDemandas($id);

//      $result = $registros->transform(function ($item)
//      {
//        return [
//          'instalacion_name'=> $item->mtinstalacions->nombre,
//          'osde_name'=> $item->mtinstalacions->entidades->osde->nombre,
//          'id'=> $item->id,
//          'tiposg_id'=> $item->mttipossistgests->id,
//          'tiposg_desc'=> $item->mttipossistgests->desc_TipoSistGestion,
//        ];
//      });

      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
      return $this->sendResponse($registro->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionRegistros->model]), $exception->getMessage(), '500');
    }
  }

  public function getRegistro(Request $request)
  {
    try {
      $input = $request->all();
      $id = $input['id'];
      $registro = $this->sistGestionRegistrosRepository->getRegistro($id);

//      $result = $registros->transform(function ($item)
//      {
//        return [
//          'instalacion_name'=> $item->mtinstalacions->nombre,
//          'osde_name'=> $item->mtinstalacions->entidades->osde->nombre,
//          'id'=> $item->id,
//          'tiposg_id'=> $item->mttipossistgests->id,
//          'tiposg_desc'=> $item->mttipossistgests->desc_TipoSistGestion,
//        ];
//      });

      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
      return $this->sendResponse($registro->toArray(), __('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionRegistros->model]), $exception->getMessage(), '500');
    }
  }

  public function addRegister(Request $request)
  {
    try {
      $input = $request->all();
      $register = $this->sistGestionRegistrosRepository->create($input);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
      return $this->sendResponse($register->toArray(), 'Registro saved successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionRegistros->model]), $exception->getMessage(), '500');
    }
  }

  public function deleteRegister($id)
  {
    try {
      $registro = $this->sistGestionRegistrosRepository->delete($id);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
      return $this->sendResponse($registro, 'Resgistro delete successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionRegistros->model]), $exception->getMessage(), '500');
    }
  }

  public function editRegister(Request $request)
  {
    try {
      $input = $request->all();
      $registro = $this->sistGestionRegistrosRepository->update($input['form_detailsregister'],$input['id']);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->sistGestionRegistros->model]));
      return $this->sendResponse($registro, 'Resgistro update successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sistGestionRegistros->model]), $exception->getMessage(), '500');
    }
  }
}
