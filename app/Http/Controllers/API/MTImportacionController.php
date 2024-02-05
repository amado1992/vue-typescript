<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateImportacionAPIRequest;
use App\Http\Requests\API\CreateRenglonAPIRequest;
use App\Http\Requests\API\UpdateMTEmpresaAPIRequest;
use App\Http\Requests\API\UpdateImportacionAPIRequest;
use App\Models\MTImportacion;
use App\Models\Traza;
use App\Repositories\MTImportacionRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTImportacionController
 * @package App\Http\Controllers\API
 */
class MTImportacionController extends AppBaseController
{
    /** @var  MTImportacionRepository */
    private $mtimportacionRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTImportacion */
    private $importacion;

    public function __construct(MTImportacionRepository $mtimportacionRepository, MTImportacion $importacion, Traza $traza)
    {
        $this->mtimportacionRepository = $mtimportacionRepository;
        $this->traza = $traza;
        $this->importacion = $importacion;
    }

    /**
     * Display a listing of the Importacion.
     * GET|HEAD /importaciones
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $importacion = $this->mtimportacionRepository->getAllPaginateImportacion($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->importacion->model]));
            return $this->sendResponse($importacion->toArray(), __('messages.app.data_retrieved', ['model' => $this->importacion->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->importacion->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created Importacion in storage.
     * POST /importaciones
     *
     * @param CreateRenglonAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateImportacionAPIRequest $request)
    {
        try {
            $input = $request->all();
            $importacion = $this->mtimportacionRepository->create($input);

            Log::info(__('messages.app.model_success', ['model' => $this->importacion->model, 'operation' => 'creada']) . ' con id = ' . $importacion->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->importacion->model, json_encode($importacion));
            return $this->sendResponse($importacion->toArray(), __('messages.app.model_success', ['model' => $this->importacion->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->importacion->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified Importacion.
     * GET|HEAD /importaciones/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTImportacion $importacion */
        $importacion = $this->mtimportacionRepository->find($id);

        if (empty($importacion)) {
            return $this->sendError('Importacion no encontrada');
        }

        return $this->sendResponse($importacion->toArray(), 'Importacion obtenida satisfactoriamente');
    }

    /**
     * Update the specified Importacion in storage.
     * PUT/PATCH /importaciones/{id}
     *
     * @param int $id
     * @param UpdateMTEmpresaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImportacionAPIRequest $request)
    {
        try {
            $input = $request->all();

            /** @var MTImportacion $importacion */
            $objActualizar = $importacion = $this->mtimportacionRepository->find($id);

            if (empty($importacion)) {
                return $this->sendError('Importacion not found');
            }

            $importacion = $this->mtimportacionRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $importacion];

            // Log::info(__('messages.app.model_success', ['model' => $this->$importacion->model, 'operation' => 'actualizada']) . ' con id = ' . $importacion->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->importacion->model, json_encode($objArray));
            return $this->sendResponse($importacion->toArray(), __('messages.app.model_success', ['model' => $this->importacion->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->importacion->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified Importacion from storage.
     * DELETE /importaciones/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTImportacion $importacion */
            $importacion = $this->mtimportacionRepository->find($id);

            if (empty($importacion)) {
                return $this->sendError('Importacion not found');
            }

            $importacion->delete();

            Log::info(__('messages.app.model_success', ['model' => $this->importacion->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->importacion->model, json_encode($importacion));
            return $this->sendSuccess('Data deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->importacion->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function searchImportacionIndicadorMes(Request $request)
    {
        /** @var MTImportacion $importacion */
        $importacion = $this->mtimportacionRepository->getImportacionIndicadorMes($request);

        if (empty($importacion)) {
            return $this->sendError('Data no encontrada');
        }

        return $this->sendResponse($importacion->toArray(), 'Data obtenida satisfactoriamente');
    }

    public function searchImportacionEmpresaMes(Request $request)
    {
        /** @var MTImportacion $importacion */
        $importacion = $this->mtimportacionRepository->getImportacionEmpresaMes($request);

        if (empty($importacion)) {
            return $this->sendError('Data no encontrada');
        }

        return $this->sendResponse($importacion->toArray(), 'Data obtenida satisfactoriamente');
    }

    public function getAllImportacion()
    {
        /** @var MTImportacion $importacion */
        $importacion = $this->mtimportacionRepository->listMTImportacion();

        if (empty($importacion)) {
            return $this->sendError('Data no encontrada');
        }

        return $this->sendResponse($importacion->toArray(), 'Data obtenida satisfactoriamente');
    }
    // Compara el plan del año actual con el año anterior
    public function compararPlan()
    {
        try {
            $data = $this->mtimportacionRepository->compararPlanImportaciones();
            return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->importacion->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->importacion->model]), $exception->getMessage(), '500');
        }
    }

    public function informacionDashboard(Request $request)
    {
        try {
            $data = $this->mtimportacionRepository->getInfo($request);
            return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->importacion->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->importacion->model]), $exception->getMessage(), '500');
        }
    }
}
