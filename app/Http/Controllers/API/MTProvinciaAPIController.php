<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTProvinciaAPIRequest;
use App\Http\Requests\API\UpdateMTProvinciaAPIRequest;
use App\Models\MTProvincia;
use App\Models\Traza;
use App\Repositories\MTProvinciaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTProvinciaController
 * @package App\Http\Controllers\API
 */
class MTProvinciaAPIController extends AppBaseController
{
    /** @var  MTProvinciaRepository */
    private $mTProvinciaRepository;

    /** @var  MTProvincia */
    private $provincia;

    /** @var  Traza */
    private $traza;

    public function __construct(MTProvinciaRepository $mTProvinciaRepo, MTProvincia $provincia, Traza $traza)
    {
        $this->mTProvinciaRepository = $mTProvinciaRepo;
        $this->provincia = $provincia;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTProvincia.
     * GET|HEAD /mTProvincias
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $provincias = $this->mTProvinciaRepository->paginate($request['itemsPerPage'], '*', $request['page'], $request['search']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->provincia->model]));
            return $this->sendResponse($provincias->toArray(), __('messages.app.data_retrieved', ['model' => $this->provincia->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->provincia->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTProvincia in storage.
     * POST /mTProvincias
     *
     * @param CreateMTProvinciaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTProvinciaAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtprovincia'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->provincia->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            $provincia = $this->mTProvinciaRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->provincia->model, 'operation' => 'creada']) . ' con id = ' . $provincia->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->provincia->model, json_encode($provincia));
            return $this->sendResponse($provincia->toArray(), __('messages.app.model_success', ['model' => $this->provincia->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->provincia->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTProvincia.
     * GET|HEAD /mTProvincias/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTProvincia $mTProvincia */
        $mTProvincia = $this->mTProvinciaRepository->find($id);

        if (empty($mTProvincia)) {
            return $this->sendError('M T Provincia not found');
        }

        return $this->sendResponse($mTProvincia->toArray(), 'M T Provincia retrieved successfully');
    }

    /**
     * Update the specified MTProvincia in storage.
     * PUT/PATCH /mTProvincias/{id}
     *
     * @param int $id
     * @param UpdateMTProvinciaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTProvinciaAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtprovincia,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->provincia->model, 'operation' => 'actualizada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTProvincia $mTProvincia */
            $objActualizar = $mTProvincia = $this->mTProvinciaRepository->find($id);

            if (empty($mTProvincia)) {
                return $this->sendError('M T Provincia not found');
            }

            $mTProvincia = $this->mTProvinciaRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTProvincia];
            Log::info(__('messages.app.model_success', ['model' => $this->provincia->model, 'operation' => 'actualizada']) . ' con id = ' . $mTProvincia->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->provincia->model, json_encode($objArray));
            return $this->sendResponse($mTProvincia->toArray(), __('messages.app.model_success', ['model' => $this->provincia->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->provincia->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTProvincia from storage.
     * DELETE /mTProvincias/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTProvincia $mTProvincia */
            $mTProvincia = $this->mTProvinciaRepository->find($id);

            if (empty($mTProvincia)) {
                return $this->sendError('M T Provincia not found');
            }

            $mTProvincia->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->provincia->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->provincia->model, json_encode($mTProvincia));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->provincia->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getProvincias(Request $request)
    {
        try {
            $provincias = $this->mTProvinciaRepository->getProvincias();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->provincia->model]));
            return $this->sendResponse($provincias->toArray(), __('messages.app.data_retrieved', ['model' => $this->provincia->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->provincia->model]), $exception->getMessage(), '500');
        }
    }
}
