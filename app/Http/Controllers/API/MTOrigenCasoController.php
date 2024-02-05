<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOrigenCasoAPIRequest;
use App\Http\Requests\API\UpdateOrigenCasoAPIRequest;
use App\Models\MTOrigenCaso;
use App\Models\Traza;
use App\Repositories\MTOrigenCasoRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTOrigenCasoController
 * @package App\Http\Controllers\API
 */
class MTOrigenCasoController extends AppBaseController
{
  /** @var  MTOrigenCasoRepository */
  private $mtOrigenCasoRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTOrigenCaso */
  private $OrigenCaso;

  public function __construct(MTOrigenCasoRepository $mtOrigenCasoRepository, MTOrigenCaso $OrigenCaso, Traza $traza)
  {
    $this->mtOrigenCasoRepository = $mtOrigenCasoRepository;
    $this->traza = $traza;
    $this->OrigenCaso = $OrigenCaso;
  }

  /**
   * Display a listing of the OrigenCaso.
   * GET|HEAD /origencaso
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $origencaso = $this->mtOrigenCasoRepository->getAllPaginateOrigenCaso($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->OrigenCaso->model]));
      return $this->sendResponse($origencaso->toArray(), __('messages.app.data_retrieved', ['model' => $this->OrigenCaso->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->OrigenCaso->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created OrigenCaso in storage.
   * POST /origencaso
   *
   * @param CreateOrigenCasoAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtorigen_casos')->count();
    $last_origencaso = MTOrigenCaso::all()->last();
    if (empty($last_origencaso))
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

  public function store(CreateOrigenCasoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'origen_caso' => 'required|unique:mtorigen_casos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->OrigenCaso->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3); //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      $input = Arr::add($input, 'codigo', $codigo);

      $origencaso = $this->mtOrigenCasoRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->OrigenCaso->model, 'operation' => 'creada']) . ' con id = ' . $origencaso->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->OrigenCaso->model, json_encode($origencaso));
      return $this->sendResponse($origencaso->toArray(), __('messages.app.model_success', ['model' => $this->OrigenCaso->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OrigenCaso->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified OrigenCaso.
   * GET|HEAD /origencaso/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTOrigenCaso $origencaso */
    $origencaso = $this->mtOrigenCasoRepository->find($id);

    if (empty($origencaso)) {
      return $this->sendError('Origen de caso no encontrada');
    }

    return $this->sendResponse($origencaso->toArray(), 'Origen de caso obtenido satisfactoriamente');
  }

  /**
   * Update the specified TipoCaso in storage.
   * PUT/PATCH /origencaso/{id}
   *
   * @param int $id
   * @param UpdateOrigenCasoAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateOrigenCasoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'origen_caso' => 'required|unique:mtorigen_casos,origen_caso,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->OrigenCaso->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var MTOrigenCaso $origencaso */
      $objActualizar = $origencaso = $this->mtOrigenCasoRepository->find($id);

      if (empty($origencaso)) {
        return $this->sendError('Origen de caso not found');
      }

      $origencaso = $this->mtOrigenCasoRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $origencaso];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->OrigenCaso->model, json_encode($objArray));
      return $this->sendResponse($origencaso->toArray(), __('messages.app.model_success', ['model' => $this->OrigenCaso->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OrigenCaso->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified OrigenCaso from storage.
   * DELETE /origencaso/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTOrigenCaso $origencaso */
      $origencaso = $this->mtOrigenCasoRepository->find($id);

      if (empty($origencaso)) {
        return $this->sendError('OrigenCaso not found');
      }

      $origencaso->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->OrigenCaso->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->OrigenCaso->model, json_encode($origencaso));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OrigenCaso->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTOrigenCaso $origencaso */
    $origencaso = $this->mtOrigenCasoRepository->listMTOrigenCaso();

    if (empty($origencaso)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($origencaso->toArray(), 'Data obtenida satisfactoriamente');
  }
}
