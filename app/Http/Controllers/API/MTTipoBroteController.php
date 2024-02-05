<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoBroteAPIRequest;
use App\Http\Requests\API\UpdateTipoBroteAPIRequest;
use App\Models\MTTipoBrote;
use App\Models\Traza;
use App\Repositories\MTTipoBroteRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTTipoBroteController
 * @package App\Http\Controllers\API
 */
class MTTipoBroteController extends AppBaseController
{
  /** @var  MTTipoBroteRepository */
  private $mtTipoBroteRepository;

  /** @var  Traza */
  private $traza;

  /** @var  MTTipoBrote */
  private $TipoBrote;

  public function __construct(MTTipoBroteRepository $mtTipoBroteRepository, MTTipoBrote $TipoBrote, Traza $traza)
  {
    $this->mtTipoBroteRepository = $mtTipoBroteRepository;
    $this->traza = $traza;
    $this->TipoBrote = $TipoBrote;
  }

  /**
   * Display a listing of the TipoBrote.
   * GET|HEAD /tipobrote
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $tipobrote = $this->mtTipoBroteRepository->getAllPaginateTipoBrote($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->TipoBrote->model]));
      return $this->sendResponse($tipobrote->toArray(), __('messages.app.data_retrieved', ['model' => $this->TipoBrote->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->TipoBrote->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created TipoBrote in storage.
   * POST /tipobrote
   *
   * @param CreateTipoBroteAPIRequest $request
   *
   * @return Response
   */

  public function generateRandomString($length)
  {
    $arreglo = DB::table('mttipo_brotes')->count();
    $last_tipobrote = MTTipoBrote::all()->last();
    if (empty($last_tipobrote))
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

  public function store(CreateTipoBroteAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'tipo_brote' => 'required|unique:mttipo_brotes',
    ]);

    if ($validate->fails())
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->TipoBrote->model, 'operation' => 'creada']), '500');

    try {
      $input = $request->all();
      $codigo = $this->generateRandomString(3); //El código estaría compuesto por 3 letras seguidas de números consecutivos (ejemplo: AGC9, LKY56536).
      $input = Arr::add($input, 'codigo', $codigo);

      $tipobrote = $this->mtTipoBroteRepository->create($input);

      Log::info(__('messages.app.model_success', ['model' => $this->TipoBrote->model, 'operation' => 'creada']) . ' con id = ' . $tipobrote->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->TipoBrote->model, json_encode($tipobrote));
      return $this->sendResponse($tipobrote->toArray(), __('messages.app.model_success', ['model' => $this->TipoBrote->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->TipoBrote->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified TipoBrote.
   * GET|HEAD /tipobrote/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var MTTipoBrote $tipobrote */
    $tipobrote = $this->mtTipoBroteRepository->find($id);

    if (empty($tipobrote)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($tipobrote->toArray(), 'Data obtenido satisfactoriamente');
  }

  /**
   * Update the specified TipoBrote in storage.
   * PUT/PATCH /tipobrote/{id}
   *
   * @param int $id
   * @param UpdateTipoBroteAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateTipoBroteAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'tipo_brote' => 'required|unique:mttipo_brotes,tipo_brote,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->TipoBrote->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var MTTipoBrote $tipobrote */
      $objActualizar = $tipobrote = $this->mtTipoBroteRepository->find($id);

      if (empty($tipobrote)) {
        return $this->sendError('Data not found');
      }

      $tipobrote = $this->mtTipoBroteRepository->update($input, $id);
      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $tipobrote];

      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->TipoBrote->model, json_encode($objArray));
      return $this->sendResponse($tipobrote->toArray(), __('messages.app.model_success', ['model' => $this->TipoBrote->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->TipoBrote->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified TipoBrote from storage.
   * DELETE /tipobrote/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var MTTipoBrote $tipobrote */
      $tipobrote = $this->mtTipoBroteRepository->find($id);

      if (empty($tipobrote)) {
        return $this->sendError('Data not found');
      }

      $tipobrote->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->TipoBrote->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->TipoBrote->model, json_encode($tipobrote));
      return $this->sendSuccess('Data deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->TipoBrote->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }

  public function get()
  {
    /** @var MTTipoBrote $tipobrote */
    $tipobrote = $this->mtTipoBroteRepository->listMTTipoBrote();

    if (empty($tipobrote)) {
      return $this->sendError('Data no encontrada');
    }

    return $this->sendResponse($tipobrote->toArray(), 'Data obtenida satisfactoriamente');
  }
}
