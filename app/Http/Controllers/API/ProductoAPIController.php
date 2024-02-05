<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductoAPIRequest;
use App\Http\Requests\API\UpdateProductoAPIRequest;
use App\Models\Familia;
use App\Models\Producto;
use App\Models\Traza;
use App\Repositories\ProductoRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class ProductoController
 * @package App\Http\Controllers\API
 */
class ProductoAPIController extends AppBaseController
{
  /** @var  ProductoRepository */
  private $productoRepository;

  /** @var  Traza */
  private $traza;

  /** @var  Producto */
  private $producto;

  public function __construct(ProductoRepository $productoRepo, Producto $producto, Traza $traza)
  {
    $this->productoRepository = $productoRepo;
    $this->traza = $traza;
    $this->producto = $producto;
  }

  /**
   * Display a listing of the Producto.
   * GET|HEAD /productos
   *
   * @param Request $request
   * @return Response
   */
  public function index(Request $request)
  {
    try {
      $producto = $this->productoRepository->getAllPaginateProducto($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->producto->model]));
      return $this->sendResponse($producto->toArray(), __('messages.app.data_retrieved', ['model' => $this->producto->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->producto->model]), $exception->getMessage(), '500');
    }
  }

  public function getProductoFamilia(Request $request)
  {
    try {
      $producto = $this->productoRepository->getAllPaginateProductoFamilia($request);
      Log::info(__('messages.app.data_retrieved', ['model' => $this->producto->model]));
      return $this->sendResponse($producto->toArray(), __('messages.app.data_retrieved', ['model' => $this->producto->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->producto->model]), $exception->getMessage(), '500');
    }
  }

  public function get(Request $request)
  {
    try {
      $producto = $this->productoRepository->getProductos();
      Log::info(__('messages.app.data_retrieved', ['model' => $this->producto->model]));
      return $this->sendResponse($producto->toArray(), __('messages.app.data_retrieved', ['model' => $this->producto->model]));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->producto->model]), $exception->getMessage(), '500');
    }
  }

  /**
   * Store a newly created Producto in storage.
   * POST /productos
   *
   * @param CreateProductoAPIRequest $request
   *
   * @return Response
   */
  public function store(CreateProductoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:producto'
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->producto->model, 'operation' => 'creada']), '500');
    }

    try {
      $input = $request->all();
      $producto = $this->productoRepository->create($input);
      Log::info(__('messages.app.model_success', ['model' => $this->producto->model, 'operation' => 'creada']) . ' con id = ' . $producto->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->producto->model, json_encode($producto));
      return $this->sendResponse($producto->toArray(), __('messages.app.model_success', ['model' => $this->producto->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->producto->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Display the specified Producto.
   * GET|HEAD /productos/{id}
   *
   * @param int $id
   *
   * @return Response
   */
  public function show($id)
  {
    /** @var Producto $producto */
    $producto = $this->productoRepository->find($id);

    if (empty($producto)) {
      return $this->sendError('Producto not found');
    }

    return $this->sendResponse($producto->toArray(), 'Producto retrieved successfully');
  }

  /**
   * Update the specified Producto in storage.
   * PUT/PATCH /productos/{id}
   *
   * @param int $id
   * @param UpdateProductoAPIRequest $request
   *
   * @return Response
   */
  public function update($id, UpdateProductoAPIRequest $request)
  {
    $validate = validator($request->all(), [
      'nombre' => 'required|unique:producto,nombre,' . $id
    ]);

    if ($validate->fails()) {
      Log::error('Existe un registro con ese nombre');
      return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->producto->model, 'operation' => 'creada']), '500');
    }
    try {
      $input = $request->all();

      /** @var Producto $producto */
      $objActualizar = $producto = $this->productoRepository->find($id);

      if (empty($producto)) {
        return $this->sendError('Producto not found');
      }

      $producto = $this->productoRepository->update($input, $id);

      $objArray = ['actualizar' => $objActualizar, 'actualizado' => $producto];
      Log::info(__('messages.app.model_success', ['model' => $this->producto->model, 'operation' => 'actualizada']) . ' con id = ' . $producto->id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->producto->model, json_encode($objArray));
      return $this->sendResponse($producto->toArray(), __('messages.app.model_success', ['model' => $this->producto->model, 'operation' => 'actualizada']));
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->producto->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
    }
  }

  /**
   * Remove the specified Producto from storage.
   * DELETE /productos/{id}
   *
   * @param int $id
   *
   * @param Request $request
   * @return Response
   */
  public function destroy($id, Request $request)
  {
    try {
      /** @var Producto $producto */
      $producto = $this->productoRepository->find($id);

      if (empty($producto)) {
        return $this->sendError('Producto not found');
      }

      $producto->delete();

      Log::info(__('messages.app.model_success', ['model' => $this->producto->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->producto->model, json_encode($producto));
      return $this->sendSuccess('Persona deleted successfully');
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->producto->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
    }
  }
}
