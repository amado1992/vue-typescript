<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTColectivoLiderAPIRequest;
use App\Http\Requests\API\UpdateMTColectivoLiderAPIRequest;
use App\Models\MTColectivoLider;
use App\Models\Traza;
use App\Repositories\MTColectivoLiderRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTColectivoLiderController
 * @package App\Http\Controllers\API
 */
class MTColectivoLiderAPIController extends AppBaseController
{
    /** @var  MTColectivoLiderRepository */
    private $mTColectivoLiderRepository;

    /** @var  MTColectivoLider */
    private $colectivo;

    /** @var  Traza */
    private $traza;

    public function __construct(MTColectivoLiderRepository $mTColectivoLiderRepo, MTColectivoLider $colectivo, Traza $traza)
    {
        $this->mTColectivoLiderRepository = $mTColectivoLiderRepo;
        $this->colectivo = $colectivo;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTColectivoLider.
     * GET|HEAD /mTColectivoLiders
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $colectivos = $this->mTColectivoLiderRepository->getAllPaginateColectivo($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
            return $this->sendResponse($colectivos->toArray(), __('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->colectivo->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTColectivoLider in storage.
     * POST /mTColectivoLiders
     *
     * @param CreateMTColectivoLiderAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTColectivoLiderAPIRequest $request)
    {
        try {
            $input = $request->all();

            $mTColectivoLider = $this->mTColectivoLiderRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->colectivo->model, 'operation' => 'creada']) . ' con id = ' . $mTColectivoLider->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->colectivo->model, json_encode($mTColectivoLider));
            return $this->sendResponse($mTColectivoLider->toArray(), __('messages.app.model_success', ['model' => $this->colectivo->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->colectivo->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTColectivoLider.
     * GET|HEAD /mTColectivoLiders/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTColectivoLider $mTColectivoLider */
        $mTColectivoLider = $this->mTColectivoLiderRepository->find($id);

        if (empty($mTColectivoLider)) {
            return $this->sendError('M T Colectivo Lider not found');
        }

        return $this->sendResponse($mTColectivoLider->toArray(), 'M T Colectivo Lider retrieved successfully');
    }

    /**
     * Update the specified MTColectivoLider in storage.
     * PUT/PATCH /mTColectivoLiders/{id}
     *
     * @param int $id
     * @param UpdateMTColectivoLiderAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTColectivoLiderAPIRequest $request)
    {
        try {
            $input = $request->all();

            /** @var MTColectivoLider $mTColectivoLider */
            $objActualizar = $mTColectivoLider = $this->mTColectivoLiderRepository->find($id);

            if (empty($mTColectivoLider)) {
                return $this->sendError('M T Colectivo Lider not found');
            }

            $mTColectivoLider = $this->mTColectivoLiderRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTColectivoLider];
            Log::info(__('messages.app.model_success', ['model' => $this->colectivo->model, 'operation' => 'actualizada']) . ' con id = ' . $mTColectivoLider->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->colectivo->model, json_encode($objArray));
            return $this->sendResponse($mTColectivoLider->toArray(), __('messages.app.model_success', ['model' => $this->colectivo->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->colectivo->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTColectivoLider from storage.
     * DELETE /mTColectivoLiders/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTColectivoLider $mTColectivoLider */
            $mTColectivoLider = $this->mTColectivoLiderRepository->find($id);

            if (empty($mTColectivoLider)) {
                return $this->sendError('M T Colectivo Lider not found');
            }

            $mTColectivoLider->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->colectivo->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->colectivo->model, json_encode($mTColectivoLider));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->colectivo->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function get_colectivos(Request $request)
    {
        try {
            $colectivos = $this->mTColectivoLiderRepository->getColectivos();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
            return $this->sendResponse($colectivos->toArray(), __('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->colectivo->model]), $exception->getMessage(), '500');
        }
    }
    public function get_colectivos_estado(Request $request)
    {
        try {
            $colectivos = $this->mTColectivoLiderRepository->getColectivosEstado($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
            return $this->sendResponse($colectivos->toArray(), __('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->colectivo->model]), $exception->getMessage(), '500');
        }
    }
    public function lista_relaciones(Request $request)
    {
        try {
            $colectivos = $this->mTColectivoLiderRepository->getListadoRelaciones($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
            return $this->sendResponse($colectivos->toArray(), __('messages.app.data_retrieved', ['model' => $this->colectivo->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->colectivo->model]), $exception->getMessage(), '500');
        }
    }
}
