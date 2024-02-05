<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRenglonAPIRequest;
use App\Http\Requests\API\UpdateRenglonAPIRequest;
use App\Models\MTRenglon;
use App\Models\Traza;
use App\Repositories\MTRenglonRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTRenglonController
 * @package App\Http\Controllers\API
 */
class MTRenglonController extends AppBaseController
{
    /** @var  MTRenglonRepository */
    private $mtrenglonRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTRenglon */
    private $renglon;

    public function __construct(MTRenglonRepository $mtrenglonRepository, MTRenglon $renglon, Traza $traza)
    {
        $this->mtrenglonRepository = $mtrenglonRepository;
        $this->traza = $traza;
        $this->renglon = $renglon;
    }

    /**
     * Display a listing of the renglon.
     * GET|HEAD /renglon
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $renglon = $this->mtrenglonRepository->listMTRenglon($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->renglon->model]));
            return $this->sendResponse($renglon->toArray(), __('messages.app.data_retrieved', ['model' => $this->renglon->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->renglon->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created renglon in storage.
     * POST /renglon
     *
     * @param CreateRenglonAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRenglonAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtrenglons'
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->renglon->model, 'operation' => 'creada']), '500');
        }

        try {
            $input = $request->all();
            $renglon = $this->mtrenglonRepository->create($input);
            Log::info(__('messages.app.model_success', ['model' => $this->renglon->model, 'operation' => 'creada']) . ' con id = ' . $renglon->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->renglon->model, json_encode($renglon));
            return $this->sendResponse($renglon->toArray(), __('messages.app.model_success', ['model' => $this->renglon->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->renglon->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified renglon.
     * GET|HEAD /renglon/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTRenglon $renglon */
        $renglon = $this->mtrenglonRepository->find($id);

        if (empty($renglon)) {
            return $this->sendError('Data no encontrada');
        }

        return $this->sendResponse($renglon->toArray(), 'Data obtenida satisfactoriamente');
    }

    /**
     * Update the specified renglon in storage.
     * PUT/PATCH /renglon/{id}
     *
     * @param int $id
     * @param UpdateRenglonAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRenglonAPIRequest $request)
    {
        $validate = validator($request->all(), [
          'nombre' => 'required|unique:mtrenglons,nombre,' .$id
        ]);

        if ($validate->fails())
        {
          Log::error('Existe un registro con ese nombre');
          return $this->sendError(__('Existe un registro con ese nombre', ['model' => $this->renglon->model, 'operation' => 'actualizada']), '500');
        }

        try {
            $input = $request->all();

            /** @var MTRenglon $renglon */
            $objActualizar = $renglon = $this->mtrenglonRepository->find($id);

            if (empty($renglon)) {
                return $this->sendError('Data not found');
            }

            $renglon = $this->mtrenglonRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $renglon];

            // Log::info(__('messages.app.model_success', ['model' => $this->$renglon->model, 'operation' => 'actualizada']) . ' con id = ' . $renglon->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->renglon->model, json_encode($objArray));
            return $this->sendResponse($renglon->toArray(), __('messages.app.model_success', ['model' => $this->renglon->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->renglon->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified renglon from storage.
     * DELETE /renglon/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTRenglon $renglon */
            $renglon = $this->mtrenglonRepository->find($id);

            if (empty($renglon)) {
                return $this->sendError('Data not found');
            }

            $renglon->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->renglon->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->renglon->model, json_encode($renglon));
            return $this->sendSuccess('data deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->renglon->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
}
