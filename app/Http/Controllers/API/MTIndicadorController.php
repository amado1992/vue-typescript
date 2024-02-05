<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateIndicadorAPIRequest;
use App\Http\Requests\API\UpdateIndicadorAPIRequest;
use App\Models\MTIndicador;
use App\Models\Traza;
use App\Repositories\MTIndicadorRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTIndicadorController
 * @package App\Http\Controllers\API
 */
class MTIndicadorController extends AppBaseController
{
    /** @var  MTIndicadorRepository */
    private $mtindicadorRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTIndicador */
    private $indicador;

    public function __construct(MTIndicadorRepository $mtindicadorRepository, MTIndicador $indicador, Traza $traza)
    {
        $this->mtindicadorRepository = $mtindicadorRepository;
        $this->traza = $traza;
        $this->indicador = $indicador;
    }

    /**
     * Display a listing of the indicador.
     * GET|HEAD /indicadores
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $indicador = $this->mtindicadorRepository->listMTIndicador($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->indicador->model]));
            return $this->sendResponse($indicador->toArray(), __('messages.app.data_retrieved', ['model' => $this->indicador->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->indicador->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created indicador in storage.
     * POST /importaciones
     *
     * @param CreateIndicadorAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateIndicadorAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtindicadors'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->indicador->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            $indicador = $this->mtindicadorRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->indicador->model, 'operation' => 'creada']) . ' con id = ' . $indicador->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->indicador->model, json_encode($indicador));
            return $this->sendResponse($indicador->toArray(), __('messages.app.model_success', ['model' => $this->indicador->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->indicador->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified indicador.
     * GET|HEAD /indicadores/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTIndicador $indicador */
        $indicador = $this->mtindicadorRepository->find($id);

        if (empty($indicador)) {
            return $this->sendError('Data no encontrada');
        }

        return $this->sendResponse($indicador->toArray(), 'Data obtenida satisfactoriamente');
    }

    /**
     * Update the specified Importacion in storage.
     * PUT/PATCH /indicadores/{id}
     *
     * @param int $id
     * @param UpdateIndicadorAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateIndicadorAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtindicadors,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->indicador->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            /** @var MTIndicador $indicador */
            $objActualizar = $indicador = $this->mtindicadorRepository->find($id);

            if (empty($indicador)) {
                return $this->sendError('Data not found');
            }

            $indicador = $this->mtindicadorRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $indicador];

            // Log::info(__('messages.app.model_success', ['model' => $this->$indicador->model, 'operation' => 'actualizada']) . ' con id = ' . $indicador->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->indicador->model, json_encode($objArray));
            return $this->sendResponse($indicador->toArray(), __('messages.app.model_success', ['model' => $this->indicador->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->indicador->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified indicador from storage.
     * DELETE /indicadores/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTIndicador $indicador */
            $indicador = $this->mtindicadorRepository->find($id);

            if (empty($indicador)) {
              return $this->sendError('Data not found');
            }
            $indicador->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->indicador->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->indicador->model, json_encode($indicador));
            return $this->sendSuccess('Data deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->indicador->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
}
