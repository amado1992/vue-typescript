<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTPropuestaLiderAPIRequest;
use App\Http\Requests\API\UpdateMTPropuestaLiderAPIRequest;
use App\Models\MTPropuestaLider;
use App\Models\Traza;
use App\Repositories\MTPropuestaLiderRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTPropuestaLiderController
 * @package App\Http\Controllers\API
 */
class MTPropuestaLiderAPIController extends AppBaseController
{
    /** @var  MTPropuestaLiderRepository */
    private $mTPropuestaLiderRepository;

    /** @var  MTPropuestaLider */
    private $propuesta;

    /** @var  Traza */
    private $traza;

    public function __construct(MTPropuestaLiderRepository $mTPropuestaLiderRepo, MTPropuestaLider $propuesta, Traza $traza)
    {
        $this->mTPropuestaLiderRepository = $mTPropuestaLiderRepo;
        $this->propuesta = $propuesta;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTPropuestaLider.
     * GET|HEAD /mTPropuestaLiders
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $propuestas = $this->mTPropuestaLiderRepository->getAllPaginatePropuesta($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
            return $this->sendResponse($propuestas->toArray(), __('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->propuesta->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTPropuestaLider in storage.
     * POST /mTPropuestaLiders
     *
     * @param CreateMTPropuestaLiderAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTPropuestaLiderAPIRequest $request)
    {
        try {
            $input = $request->all();

            $mTPropuestaLider = $this->mTPropuestaLiderRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->propuesta->model, 'operation' => 'creada']) . ' con id = ' . $mTPropuestaLider->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->propuesta->model, json_encode($mTPropuestaLider));
            return $this->sendResponse($mTPropuestaLider->toArray(), __('messages.app.model_success', ['model' => $this->propuesta->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->propuesta->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTPropuestaLider.
     * GET|HEAD /mTPropuestaLiders/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTPropuestaLider $mTPropuestaLider */
        $mTPropuestaLider = $this->mTPropuestaLiderRepository->find($id);

        if (empty($mTPropuestaLider)) {
            return $this->sendError('M T Propuesta Lider not found');
        }

        return $this->sendResponse($mTPropuestaLider->toArray(), 'M T Propuesta Lider retrieved successfully');
    }

    /**
     * Update the specified MTPropuestaLider in storage.
     * PUT/PATCH /mTPropuestaLiders/{id}
     *
     * @param int $id
     * @param UpdateMTPropuestaLiderAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTPropuestaLiderAPIRequest $request)
    {
        try {
            $input = $request->all();

            /** @var MTPropuestaLider $mTPropuestaLider */
            $objActualizar = $mTPropuestaLider = $this->mTPropuestaLiderRepository->find($id);

            if (empty($mTPropuestaLider)) {
                return $this->sendError('M T Propuesta Lider not found');
            }

            $mTPropuestaLider = $this->mTPropuestaLiderRepository->update($input, $id);

            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTPropuestaLider];
            Log::info(__('messages.app.model_success', ['model' => $this->propuesta->model, 'operation' => 'actualizada']) . ' con id = ' . $mTPropuestaLider->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->propuesta->model, json_encode($objArray));
            return $this->sendResponse($mTPropuestaLider->toArray(), __('messages.app.model_success', ['model' => $this->propuesta->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->propuesta->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTPropuestaLider from storage.
     * DELETE /mTPropuestaLiders/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTPropuestaLider $mTPropuestaLider */
            $mTPropuestaLider = $this->mTPropuestaLiderRepository->find($id);

            if (empty($mTPropuestaLider)) {
                return $this->sendError('M T Propuesta Lider not found');
            }

            $mTPropuestaLider->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->propuesta->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->propuesta->model, json_encode($mTPropuestaLider));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->propuesta->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function get_propuestas(Request $request)
    {
        try {
            $propuestas = $this->mTPropuestaLiderRepository->getPropuestas();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
            return $this->sendResponse($propuestas->toArray(), __('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->propuesta->model]), $exception->getMessage(), '500');
        }
    }
    public function get_propuestas_estado(Request $request)
    {
        try {
            $propuestas = $this->mTPropuestaLiderRepository->getPropuestasEstado($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
            return $this->sendResponse($propuestas->toArray(), __('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->propuesta->model]), $exception->getMessage(), '500');
        }
    }
    public function lista_relaciones_propuesta(Request $request)
    {
        try {
            $propuestas = $this->mTPropuestaLiderRepository->getListadoRelaciones($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
            return $this->sendResponse($propuestas->toArray(), __('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->propuesta->model]), $exception->getMessage(), '500');
        }
    }

    public function cantPropuestasXEstado(Request $request)
    {
      try {
        $data = $this->mTPropuestaLiderRepository->getCantPropuestasXEstado($request);
        return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
      } catch (\Exception $exception) {
        return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->propuesta->model]), $exception->getMessage(), '500');
      }
    }
    public function cantPropuestasXSector(Request $request)
    {
      try {
        $data = $this->mTPropuestaLiderRepository->getCantPropuestasXSector($request);
        return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->propuesta->model]));
      } catch (\Exception $exception) {
        return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->propuesta->model]), $exception->getMessage(), '500');
      }
    }
}
