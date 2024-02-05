<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEntidadAPIRequest;
use App\Http\Requests\API\UpdateEntidadAPIRequest;
use App\Models\MTEntidad;
use App\Models\Traza;
use App\Repositories\MTEntidadRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTEntidadController
 * @package App\Http\Controllers\API
 */
class MTEntidadController extends AppBaseController
{
    /** @var  MTEntidadRepository */
    private $mtentidadRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTEntidad */
    private $entidad;

    public function __construct(MTEntidadRepository $mtentidadRepository, MTEntidad $entidad, Traza $traza)
    {
        $this->mtentidadRepository = $mtentidadRepository;
        $this->traza = $traza;
        $this->entidad = $entidad;
    }

    /**
     * Display a listing of the Entidad.
     * GET|HEAD /entidades
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $entidad = $this->mtentidadRepository->listMTEntidad($request);

            Log::info(__('messages.app.data_retrieved', ['model' => $this->entidad->model]));
            return $this->sendResponse($entidad->toArray(), __('messages.app.data_retrieved', ['model' => $this->entidad->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->entidad->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created Entidad in storage.
     * POST /entidades
     *
     * @param CreateImportacionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEntidadAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtentidad'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe una entidad con ese nombre');
          return $this->sendError(__('Existe una entidad con ese nombre', ['model' => $this->entidad->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            $entidad = $this->mtentidadRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'creada']) . ' con id = ' . $entidad->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->entidad->model, json_encode($entidad));
            return $this->sendResponse($entidad->toArray(), __('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->entidad->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified Importacion.
     * GET|HEAD /entidades/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTEntidad $entidad */
        $entidad = $this->mtentidadRepository->find($id);

        if (empty($entidad)) {
            return $this->sendError('Entidad no encontrada');
        }

        return $this->sendResponse($entidad->toArray(), 'Datos obtenidos satisfactoriamente');
    }

    /**
     * Update the specified Importacion in storage.
     * PUT/PATCH /entidades/{id}
     *
     * @param int $id
     * @param UpdateEntidadAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEntidadAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtentidad,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe una entidad con ese nombre');
          return $this->sendError(__('Existe una entidad con ese nombre', ['model' => $this->entidad->model, 'operation' => 'actualizada']), '500');

        }

        try {
            $input = $request->all();

            /** @var MTEntidad $entidad */
            $objActualizar = $entidad = $this->mtentidadRepository->find($id);

            if (empty($entidad)) {
                return $this->sendError('Entidad not found');
            }

            $entidad = $this->mtentidadRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $entidad];

            // Log::info(__('messages.app.model_success', ['model' => $this->$importacion->model, 'operation' => 'actualizada']) . ' con id = ' . $importacion->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->entidad->model, json_encode($objArray));
            return $this->sendResponse($entidad->toArray(), __('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->entidad->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified Importacion from storage.
     * DELETE /entidades/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTEntidad $entidad */
            $entidad = $this->mtentidadRepository->find($id);

            if (empty($entidad)) {
                return $this->sendError('Entidad not found');
            }

            $entidad->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->entidad->model, json_encode($entidad));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->entidad->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
}
