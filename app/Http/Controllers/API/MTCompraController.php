<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\MTCompra;
use App\Models\Traza;
use App\Http\Resources\MTCompraResource;
use App\Repositories\MTCompraRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MTCompraController extends AppBaseController
{
  private $mtCompraRepository;
  private $mtCompra;
  private $traza;

  public function __construct(MTCompraRepository $mtCompraRepository, MTCompra $mtCompra, Traza $traza)
  {
    $this->mtCompraRepository = $mtCompraRepository;
    $this->mtCompra = $mtCompra;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtCompraRepository->listMTCompra();
    return $data;
  }
  public function store(Request $request)
  {
    try {
      $user = Auth::user();
      $mtCompra = $this->mtCompraRepository->createMTCompra($request->all(), $user->instalacion_id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtCompra->model, json_encode($mtCompra));
      return $this->sendResponse($mtCompra, __('messages.app.model_success', ['model' => $this->mtCompra->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtCompra->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  public function show($id)
  {
    $data = MTCompra::findOrFail($id);
    return new MTCompraResource($data);
  }

  public function update(Request $request, $id)
  {
    try {
      $data = $this->mtCompraRepository->updateMTCompra($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtCompra->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Compra actualizada OK']);
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtCompraRepository->eliminarMTCompra($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtCompra->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtCompra->model, json_encode($data));
      return $data;
    } catch
    (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
    }
  }

  public function compararvolumenrealsxmesxfamiliaproducto(Request $request)
  {
    try {
      $data = $this->mtCompraRepository->compararvolumenrealsxmesxfamiliaproducto($request);
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
    }
  }

  public function compararprecioxempresaxvolumenreal(Request $request)
  {
    try {
      $data = $this->mtCompraRepository->compararprecioxempresaxvolumenreal($request);
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
    }
  }

  public function compararpreciosxproductoxterritorio(Request $request)
  {
    try {
      $data = $this->mtCompraRepository->compararpreciosxproductoxterritorio($request);
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
    }
  }

  public function volumeninventarioxterritorioxfamiliadelproducto(Request $request)
  {
    try {
      $data = $this->mtCompraRepository->volumeninventarioxterritorioxfamiliadelproducto($request);
      return $data;
    } catch
    (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
    }
  }
  public function dashboardInfo(Request $request)
  {
      try {
          $data = $this->mtCompraRepository->dashboardCantCompras($request);
          return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->mtCompra->model]));
      } catch (\Exception $exception) {
          return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtCompra->model]), $exception->getMessage(), '500');
      }
  }
}
