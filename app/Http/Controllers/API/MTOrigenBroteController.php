<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOrigenBroteAPIRequest;
use App\Http\Requests\API\UpdateOrigenBroteAPIRequest;
use App\Models\MTOrigenBrote;
use App\Models\Traza;
use App\Repositories\MTOrigenBroteRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTOrigenBroteController
 * @package App\Http\Controllers\API
 */
class MTOrigenBroteController extends AppBaseController
{
  /** @var  MTOrigenBroteRepository */
  private $mtOrigenBroteRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTOrigenBrote */
  private $OrigenBrote;

  public function __construct(MTOrigenBroteRepository $mtOrigenBroteRepository, MTOrigenBrote $OrigenBrote, Traza $traza)
  {
    $this->mtOrigenBroteRepository = $mtOrigenBroteRepository;
    $this->traza = $traza;
    $this->OrigenBrote = $OrigenBrote;
  }

  /**
   * Display a listing of the OrigenBrote.
   * GET|HEAD /origenbrote
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $origenbrote = $this->mtOrigenBroteRepository->getAllPaginateOrigenBrote($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->OrigenBrote->model]));
      return $this->sendResponse($origenbrote->toArray(), __('messages.app.data_retrieved', ['model' => $this->OrigenBrote->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->OrigenBrote->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created OrigenBrote in storage.
   * POST /origenbrote
   *
   * @param CreateOrigenBroteAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mtorigen_brotes')->count();
    $last_origenbrote = MTOrigenBrote::all()->last();
    if (empty($last_origenbrote))
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

  public function store(CreateOrigenBroteAPIRequest $request)
  {
    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3); //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      $input = Arr::add($input, 'codigo', $codigo);

      $origenbrote = $this->mtOrigenBroteRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->OrigenBrote->model, 'operation' => 'creada']) . ' con id = ' . $origenbrote->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->OrigenBrote->model, json_encode($origenbrote));
      return $this->sendResponse($origenbrote->toArray(), __('messages.app.model_success', ['model' => $this->OrigenBrote->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OrigenBrote->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified OrigenBrote.
   * GET|HEAD /origenbrote/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTOrigenBrote $origenbrote */
    $origenbrote = $this->mtOrigenBroteRepository->find($id);

    if (empty($origenbrote)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($origenbrote->toArray(), 'Data obtenido satisfactoriamente');
  }

  /**
   * Update the specified OrigenBrote in storage.
   * PUT/PATCH /origenbrote/{id}
   *
   * @param int $id
   * @param UpdateOrigenBroteAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateOrigenBroteAPIRequest $request)
  {
    try {
      $input = $request->all();

      /** @var MTOrigenBrote $origenbrote */
      $objActualizar = $origenbrote = $this->mtOrigenBroteRepository->find($id);

      if (empty($origenbrote)) {
        return $this->sendError('Data not found');
      }

      $origenbrote = $this->mtOrigenBroteRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $origenbrote];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->OrigenBrote->model, json_encode($objArray));
      return $this->sendResponse($origenbrote->toArray(), __('messages.app.model_success', ['model' => $this->OrigenBrote->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OrigenBrote->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified OrigenBrote from storage.
   * DELETE /origenbrote/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTOrigenBrote $origenbrote */
      $origenbrote = $this->mtOrigenBroteRepository->find($id);

      if (empty($origenbrote)) {
        return $this->sendError('Data not found');
      }

      $origenbrote->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->OrigenBrote->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->OrigenBrote->model, json_encode($origenbrote));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->OrigenBrote->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTOrigenBrote $origenbrote */
    $origenbrote = $this->mtOrigenBroteRepository->listMTOrigenBrote();

    if (empty($origenbrote)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($origenbrote->toArray(), 'Data obtenida satisfactoriamente');
  }
}
