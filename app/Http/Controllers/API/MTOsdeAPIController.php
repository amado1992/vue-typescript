<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTOsdeAPIRequest;
use App\Http\Requests\API\UpdateMTOsdeAPIRequest;
use App\Models\MTOsde;
use App\Models\Traza;
use App\Repositories\MTOsdeRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTOsdeController
 * @package App\Http\Controllers\API
 */
class MTOsdeAPIController extends AppBaseController
{
    /** @var  MTOsdeRepository */
    private $mTOsdeRepository;

    /** @var  MTOsde */
    private $osde;

    /** @var  Traza */
    private $traza;

    public function __construct(MTOsdeRepository $mTOsdeRepo, Traza $traza, MTOsde $osde)
    {
        $this->mTOsdeRepository = $mTOsdeRepo;
        $this->osde = $osde;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTOsde.
     * GET|HEAD /mTOsdes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
      try {
        $osdes = $this->mTOsdeRepository->getAllPaginateOsde($request);
        Log::info(__('messages.app.data_retrieved', ['model' => $this->osde->model]));
        return $this->sendResponse($osdes->toArray(), __('messages.app.data_retrieved', ['model' => $this->osde->model]));
      } catch (\Exception $exception) {
        Log::error($exception->getMessage());
        return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->osde->model]), $exception->getMessage(), '500');
      }
    }

    /**
     * Store a newly created MTOsde in storage.
     * POST /mTOsdes
     *
     * @param CreateMTOsdeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTOsdeAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtosde'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->osde->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            $osde = $this->mTOsdeRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->osde->model, 'operation' => 'creada']) . ' con id = ' . $osde->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->osde->model, json_encode($osde));
            return $this->sendResponse($osde->toArray(), __('messages.app.model_success', ['model' => $this->osde->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->osde->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTOsde.
     * GET|HEAD /mTOsdes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTOsde $mTOsde */
        $mTOsde = $this->mTOsdeRepository->find($id);

        if (empty($mTOsde)) {
            return $this->sendError('M T Osde not found');
        }

        return $this->sendResponse($mTOsde->toArray(), 'M T Osde retrieved successfully');
    }

    /**
     * Update the specified MTOsde in storage.
     * PUT/PATCH /mTOsdes/{id}
     *
     * @param int $id
     * @param UpdateMTOsdeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTOsdeAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtosde,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->osde->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTOsde $mTOsde */
            $objActualizar = $mTOsde = $this->mTOsdeRepository->find($id);

            if (empty($mTOsde)) {
                return $this->sendError('M T Osde not found');
            }

            $mTOsde = $this->mTOsdeRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTOsde];
            Log::info(__('messages.app.model_success', ['model' => $this->osde->model, 'operation' => 'actualizada']) . ' con id = ' . $mTOsde->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->osde->model, json_encode($objArray));
            return $this->sendResponse($mTOsde->toArray(), __('messages.app.model_success', ['model' => $this->osde->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->osde->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTOsde from storage.
     * DELETE /mTOsdes/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTOsde $mTOsde */
            $mTOsde = $this->mTOsdeRepository->find($id);

            if (empty($mTOsde)) {
                return $this->sendError('M T Osde not found');
            }

            $mTOsde->delete();


            Log::info(__('messages.app.model_success', ['model' => $this->osde->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->osde->model, json_encode($mTOsde));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->osde->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
    public function getOsdes(Request $request)
    {
        try {
            $osdes = $this->mTOsdeRepository->getOsdes();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->osde->model]));
            return $this->sendResponse($osdes->toArray(), __('messages.app.data_retrieved', ['model' => $this->osde->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->osde->model]), $exception->getMessage(), '500');
        }
    }
}
