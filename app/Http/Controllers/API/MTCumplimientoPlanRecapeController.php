<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\MTActualizacionesPlanRecape as ModelsMTActualizacionesPlanRecape;
use App\Models\Traza;
use App\Repositories\MTActualizacionesPlanRecapeRepository;
use App\Utils\EventsUtil;

class MTCumplimientoPlanRecapeController extends AppBaseController
{
    /** @var MTActualizacionesPlanRecapeRepository */
    private $cumplimientoRepository;

    /** @var ModelsMTActualizacionesPlanRecape */
    private $cumplimientoModel;

    /** @var Traza */
    private $traza;

    public function __construct(MTActualizacionesPlanRecapeRepository $cumplimientoRepository, ModelsMTActualizacionesPlanRecape $cumplimientoModel, Traza $traza)
    {
        $this->cumplimientoRepository = $cumplimientoRepository;
        $this->cumplimientoModel = $cumplimientoModel;
        $this->traza = $traza;
    }

    public function index(Request $request)
    {
        try {
            $cumplimiento = $this->cumplimientoRepository->listActualizaciones_($request);
            return $this->sendResponse($cumplimiento, __('messages.app.data_retrieved', ['model' => $this->cumplimientoModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->cumplimientoModel->model]), $exception->getMessage(), '500');
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $cumplimiento = $this->cumplimientoRepository->create($input);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->cumplimientoModel->model, json_encode($cumplimiento));
            return $this->sendResponse($cumplimiento->toArray(), __('messages.app.model_success', ['model' => $this->cumplimientoModel->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->cumplimientoModel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    public function show($id)
    {
        $cumplimiento = $this->cumplimientoRepository->find($id);

        if (empty($cumplimiento)) {
            return $this->sendError('Datos no encontrados');
        }

        return $this->sendResponse($cumplimiento->toArray(), 'Datos obtenidos satisfactoriamente');
    }

    public function update($id, Request $request)
    {
        try {
            $input = $request->all();

            $objActualizar = $cumplimiento = $this->cumplimientoRepository->find($id);

            if (empty($cumplimiento)) {
                return $this->sendError('Dato no encontrado');
            }

            $cumplimiento = $this->cumplimientoRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $cumplimiento];

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->cumplimientoModel->model, json_encode($objArray));
            return $this->sendResponse($cumplimiento->toArray(), __('messages.app.model_success', ['model' => $this->cumplimientoModel->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->cumplimientoModel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $cumplimiento = $this->cumplimientoModel->find($id);

            if (empty($cumplimiento)) {
                return $this->sendError('Dato no encontrado');
            }

            $cumplimiento->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->cumplimientoModel->model, json_encode($cumplimiento));
            return $this->sendSuccess('Dato eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->cumplimientoModel->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function reporteCumplimientoDelPlan(Request $request)
    {
        try {
            $reporte = $this->cumplimientoRepository->reporteCumplimientoPR($request);
            return $this->sendResponse($reporte, __('messages.app.data_retrieved', ['model' => $this->cumplimientoModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->cumplimientoModel->model]), $exception->getMessage(), '500');
        }
    }
}
