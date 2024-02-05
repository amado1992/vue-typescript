<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTEntidadAPIRequest;
use App\Http\Requests\API\UpdateMTEntidadAPIRequest;
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
class MTEntidadAPIController extends AppBaseController
{
    /** @var  MTEntidadRepository */
    private $mTEntidadRepository;

    /** @var  MTEntidad */
    private $entidad;

    /** @var  Traza */
    private $traza;

    public function __construct(MTEntidadRepository $mTEntidadRepo, MTEntidad $entidad, Traza $traza)
    {
        $this->mTEntidadRepository = $mTEntidadRepo;
        $this->traza = $traza;
        $this->entidad = $entidad;
    }

    /**
     * Display a listing of the MTEntidad.
     * GET|HEAD /mTEntidads
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $entidades = $this->mTEntidadRepository->getAllPaginateEntidad($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->entidad->model]));
            return $this->sendResponse($entidades->toArray(), __('messages.app.data_retrieved', ['model' => $this->entidad->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->entidad->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTEntidad in storage.
     * POST /mTEntidads
     *
     * @param CreateMTEntidadAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTEntidadAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtentidad',
          'codigo' => 'required|unique:mtentidad'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre o código');
          return $this->sendError(__('Existe un registro con ese nombre o código', ['model' => $this->entidad->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            $entidad = $this->mTEntidadRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'creada']) . ' con id = ' . $entidad->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->entidad->model, json_encode($entidad));
            return $this->sendResponse($entidad->toArray(), __('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->entidad->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTEntidad.
     * GET|HEAD /mTEntidads/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTEntidad $mTEntidad */
        $mTEntidad = $this->mTEntidadRepository->find($id);

        if (empty($mTEntidad)) {
            return $this->sendError('M T Entidad not found');
        }

        return $this->sendResponse($mTEntidad->toArray(), 'M T Entidad retrieved successfully');
    }

    /**
     * Update the specified MTEntidad in storage.
     * PUT/PATCH /mTEntidads/{id}
     *
     * @param int $id
     * @param UpdateMTEntidadAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTEntidadAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtentidad,nombre,' .$id,
          'codigo' => 'required|unique:mtentidad,codigo,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre o código');
          return $this->sendError(__('Existe un registro con ese nombre o código', ['model' => $this->entidad->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTEntidad $mTEntidad */
            $objActualizar = $mTEntidad = $this->mTEntidadRepository->find($id);

            if (empty($mTEntidad)) {
                return $this->sendError('M T Entidad not found');
            }

            $mTEntidad = $this->mTEntidadRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTEntidad];
            Log::info(__('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'actualizada']) . ' con id = ' . $mTEntidad->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->entidad->model, json_encode($objArray));
            return $this->sendResponse($mTEntidad->toArray(), __('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->entidad->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTEntidad from storage.
     * DELETE /mTEntidads/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTEntidad $mTEntidad */
            $mTEntidad = $this->mTEntidadRepository->find($id);

            if (empty($mTEntidad)) {
                return $this->sendError('M T Entidad not found');
            }

            $mTEntidad->delete();


            Log::info(__('messages.app.model_success', ['model' => $this->entidad->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->entidad->model, json_encode($mTEntidad));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->entidad->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getEntidades(Request $request)
    {
        try {
            $entidad = $this->mTEntidadRepository->getEntidades();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->entidad->model]));
            return $this->sendResponse($entidad->toArray(), __('messages.app.data_retrieved', ['model' => $this->entidad->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->entidad->model]), $exception->getMessage(), '500');
        }
    }
}
