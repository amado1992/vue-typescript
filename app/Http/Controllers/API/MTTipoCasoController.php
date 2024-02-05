<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoCasoAPIRequest;
use App\Http\Requests\API\UpdateTipoCasoAPIRequest;
use App\Models\MTTipoCaso;
use App\Models\Traza;
use App\Repositories\MTTipoCasoRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTTipoCasoController
 * @package App\Http\Controllers\API
 */
class MTTipoCasoController extends AppBaseController
{
  /** @var  MTTipoCasoRepository */
  private $mtTipoCasoRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTTipoCaso */
  private $TipoCaso;

  public function __construct(MTTipoCasoRepository $mtTipoCasoRepository, MTTipoCaso $TipoCaso, Traza $traza)
  {
    $this->mtTipoCasoRepository = $mtTipoCasoRepository;
    $this->traza = $traza;
    $this->TipoCaso = $TipoCaso;
  }

  /**
   * Display a listing of the TipoCaso.
   * GET|HEAD /tipocaso
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $tipocaso = $this->mtTipoCasoRepository->getAllPaginateTipoCaso($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->TipoCaso->model]));
      return $this->sendResponse($tipocaso->toArray(), __('messages.app.data_retrieved', ['model' => $this->TipoCaso->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->TipoCaso->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created TipoCaso in storage.
   * POST /tipocaso
   *
   * @param CreateTipoCasoAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mttipo_casos')->count();
    $last_tipocaso = MTTipoCaso::all()->last();
    if (empty($last_tipocaso))
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

  public function store(CreateTipoCasoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'tipo_caso' => 'required|unique:mttipo_casos',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->TipoCaso->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3); //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      $input = Arr::add($input, 'codigo', $codigo);

      $tipocaso = $this->mtTipoCasoRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->TipoCaso->model, 'operation' => 'creada']) . ' con id = ' . $tipocaso->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->TipoCaso->model, json_encode($tipocaso));
      return $this->sendResponse($tipocaso->toArray(), __('messages.app.model_success', ['model' => $this->TipoCaso->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->TipoCaso->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified TipoCaso.
   * GET|HEAD /tipocaso/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTTipoCaso $tipocaso */
    $tipocaso = $this->mtTipoCasoRepository->find($id);

    if (empty($tipocaso)) {
      return $this->sendError('Tipo de caso no encontrada');
    }

    return $this->sendResponse($tipocaso->toArray(), 'Tipo de caso obtenido satisfactoriamente');
  }

  /**
   * Update the specified TipoCaso in storage.
   * PUT/PATCH /tipocaso/{id}
   *
   * @param int $id
   * @param UpdateTipoCasoAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateTipoCasoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'tipo_caso' => 'required|unique:mttipo_casos,tipo_caso,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->TipoCaso->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var MTTipoCaso $tipocaso */
      $objActualizar = $tipocaso = $this->mtTipoCasoRepository->find($id);

      if (empty($tipocaso)) {
        return $this->sendError('Tipo de caso not found');
      }

      $tipocaso = $this->mtTipoCasoRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $tipocaso];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->TipoCaso->model, json_encode($objArray));
      return $this->sendResponse($tipocaso->toArray(), __('messages.app.model_success', ['model' => $this->TipoCaso->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->TipoCaso->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified TipoCaso from storage.
   * DELETE /tipocaso/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTTipoCaso $tipocaso */
      $tipocaso = $this->mtTipoCasoRepository->find($id);

      if (empty($tipocaso)) {
        return $this->sendError('TipoCaso not found');
      }

      $tipocaso->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->TipoCaso->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->TipoCaso->model, json_encode($tipocaso));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->TipoCaso->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTTipoCaso $tipocaso */
    $tipocaso = $this->mtTipoCasoRepository->listMTTipoCaso();

    if (empty($tipocaso)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($tipocaso->toArray(), 'Data obtenida satisfactoriamente');
  }
}
