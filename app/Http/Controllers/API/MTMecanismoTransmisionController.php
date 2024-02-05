<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMecanismoTransmisionAPIRequest;
use App\Http\Requests\API\UpdateMecanismoTransmisionAPIRequest;
use App\Models\MTMecanismoTransmision;
use App\Models\Traza;
use App\Repositories\MTMecanismoTransmisionRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTMecanismoTransmisionController
 * @package App\Http\Controllers\API
 */
class MTMecanismoTransmisionController extends AppBaseController
{
  /** @var  MTMecanismoTransmisionRepository */
  private $mtMecanismoTransmisionRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTMecanismoTransmision */
  private $MecanismoTransmision;

  public function __construct(MTMecanismoTransmisionRepository $mtMecanismoTransmisionRepository, MTMecanismoTransmision $MecanismoTransmision, Traza $traza)
  {
    $this->mtMecanismoTransmisionRepository = $mtMecanismoTransmisionRepository;
    $this->traza = $traza;
    $this->MecanismoTransmision = $MecanismoTransmision;
  }

  /**
   * Display a listing of the MecanismoTransmision.
   * GET|HEAD /mecanismotransmision
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $mecanismotransmision = $this->mtMecanismoTransmisionRepository->getAllPaginateMecanismoTransmision($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->MecanismoTransmision->model]));
      return $this->sendResponse($mecanismotransmision->toArray(), __('messages.app.data_retrieved', ['model' => $this->MecanismoTransmision->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->MecanismoTransmision->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created MecanismoTransmision in storage.
   * POST /mecanismotransmision
   *
   * @param CreateMecanismoTransmisionAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtmecanismo_transmisions')->count();
    $last_mecanismotransmision = MTMecanismoTransmision::all()->last();
    if (empty($last_mecanismotransmision))
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

  public function store(CreateMecanismoTransmisionAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'mecanismo_transmision' => 'required|unique:mtmecanismo_transmisions',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->MecanismoTransmision->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3); //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      $input = Arr::add($input, 'codigo', $codigo);

      $mecanismotransmision = $this->mtMecanismoTransmisionRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->MecanismoTransmision->model, 'operation' => 'creada']) . ' con id = ' . $mecanismotransmision->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->MecanismoTransmision->model, json_encode($mecanismotransmision));
      return $this->sendResponse($mecanismotransmision->toArray(), __('messages.app.model_success', ['model' => $this->MecanismoTransmision->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->MecanismoTransmision->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified MecanismoTransmision.
   * GET|HEAD /mecanismotransmision/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTMecanismoTransmision $mecanismotransmision */
    $mecanismotransmision = $this->mtMecanismoTransmisionRepository->find($id);

    if (empty($mecanismotransmision)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($mecanismotransmision->toArray(), 'Data obtenido satisfactoriamente');
  }

  /**
   * Update the specified MecanismoTransmision in storage.
   * PUT/PATCH /mecanismotransmision/{id}
   *
   * @param int $id
   * @param UpdateMecanismoTransmisionAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateMecanismoTransmisionAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'mecanismo_transmision' => 'required|unique:mtmecanismo_transmisions,mecanismo_transmision,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->MecanismoTransmision->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var MTMecanismoTransmision $mecanismotransmision */
      $objActualizar = $mecanismotransmision = $this->mtMecanismoTransmisionRepository->find($id);

      if (empty($mecanismotransmision)) {
        return $this->sendError('Data not found');
      }

      $mecanismotransmision = $this->mtMecanismoTransmisionRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mecanismotransmision];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->MecanismoTransmision->model, json_encode($objArray));
      return $this->sendResponse($mecanismotransmision->toArray(), __('messages.app.model_success', ['model' => $this->MecanismoTransmision->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->MecanismoTransmision->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified MecanismoTransmision from storage.
   * DELETE /mecanismotransmision/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTMecanismoTransmision $mecanismotransmision */
      $mecanismotransmision = $this->mtMecanismoTransmisionRepository->find($id);

      if (empty($mecanismotransmision)) {
        return $this->sendError('Data not found');
      }

      $mecanismotransmision->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->MecanismoTransmision->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->MecanismoTransmision->model, json_encode($mecanismotransmision));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->MecanismoTransmision->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTMecanismoTransmision $mecanismotransmision */
    $mecanismotransmision = $this->mtMecanismoTransmisionRepository->listMTMecanismoTransmision();

    if (empty($mecanismotransmision)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($mecanismotransmision->toArray(), 'Data obtenida satisfactoriamente');
  }
}
