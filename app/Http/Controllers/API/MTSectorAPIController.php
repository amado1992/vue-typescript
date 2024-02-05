<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTSectorAPIRequest;
use App\Http\Requests\API\UpdateMTSectorAPIRequest;
use App\Models\MTSector;
use App\Models\Traza;
use App\Repositories\MTSectorRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTSectorController
 * @package App\Http\Controllers\API
 */
class MTSectorAPIController extends AppBaseController
{
    /** @var  MTSectorRepository */
    private $mTSectorRepository;

    /** @var  MTSector */
    private $sector;

    /** @var  Traza */
    private $traza;

    public function __construct(MTSectorRepository $mTSectorRepo, MTSector $sector, Traza $traza)
    {
        $this->mTSectorRepository = $mTSectorRepo;
        $this->traza = $traza;
        $this->sector = $sector;
    }

    /**
     * Display a listing of the MTSector.
     * GET|HEAD /mTSectors
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $sector = $this->mTSectorRepository->paginate($request['itemsPerPage'], '*', $request['page'], $request['search']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->sector->model]));
            return $this->sendResponse($sector->toArray(), __('messages.app.data_retrieved', ['model' => $this->sector->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sector->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTSector in storage.
     * POST /mTSectors
     *
     * @param CreateMTSectorAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTSectorAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtsector'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->sector->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            $mTSector = $this->mTSectorRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->sector->model, 'operation' => 'creada']) . ' con id = ' . $mTSector->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->sector->model, json_encode($mTSector));
            return $this->sendResponse($mTSector->toArray(), __('messages.app.model_success', ['model' => $this->sector->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->sector->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTSector.
     * GET|HEAD /mTSectors/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTSector $mTSector */
        $mTSector = $this->mTSectorRepository->find($id);

        if (empty($mTSector)) {
            return $this->sendError('M T Sector not found');
        }

        return $this->sendResponse($mTSector->toArray(), 'M T Sector retrieved successfully');
    }

    /**
     * Update the specified MTSector in storage.
     * PUT/PATCH /mTSectors/{id}
     *
     * @param int $id
     * @param UpdateMTSectorAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTSectorAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtsector,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->sector->model, 'operation' => 'actualizada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTSector $mTSector */
            $objActualizar = $mTSector = $this->mTSectorRepository->find($id);

            if (empty($mTSector)) {
                return $this->sendError('M T Sector not found');
            }

            $mTSector = $this->mTSectorRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTSector];
            Log::info(__('messages.app.model_success', ['model' => $this->sector->model, 'operation' => 'actualizada']) . ' con id = ' . $mTSector->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->sector->model, json_encode($objArray));
            return $this->sendResponse($mTSector->toArray(), __('messages.app.model_success', ['model' => $this->sector->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->sector->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTSector from storage.
     * DELETE /mTSectors/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTSector $mTSector */
            $mTSector = $this->mTSectorRepository->find($id);

            if (empty($mTSector)) {
                return $this->sendError('M T Sector not found');
            }

            $mTSector->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->sector->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->sector->model, json_encode($mTSector));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->sector->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getSectores(Request $request)
    {
        try {
            $sectores = $this->mTSectorRepository->getSectores();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->sector->model]));
            return $this->sendResponse($sectores->toArray(), __('messages.app.data_retrieved', ['model' => $this->sector->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->sector->model]), $exception->getMessage(), '500');
        }
    }
}
