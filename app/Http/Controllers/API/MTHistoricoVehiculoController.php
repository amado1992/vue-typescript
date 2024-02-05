<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\MTHistoricoVehiculo as ModelsMTHistoricoVehiculo;
use App\Models\Traza;
use App\Repositories\MTHistoricoVehiculoRepository;
use App\Utils\EventsUtil;

class MTHistoricoVehiculoController extends AppBaseController
{
    /** @var MTHistoricoVehiculoRepository */
    private $historicoVehiculoRepository;

    /** @var ModelsMTHistoricoVehiculo */
    private $historicoVehiculoModel;

    /** @var Traza */
    private $traza;

    public function __construct(MTHistoricoVehiculoRepository $historicoVehiculoRepository, ModelsMTHistoricoVehiculo $historicoVehiculoModel, Traza $traza)
    {
        $this->historicoVehiculoRepository = $historicoVehiculoRepository;
        $this->historicoVehiculoModel = $historicoVehiculoModel;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the MTHistoricoVehiculo
     *
     * @return Response
     */
    public function listHistoricoVehiculo(Request $request)
    {
        try {
            $input = $request->all();
            $historico = $this->historicoVehiculoRepository->listHistoricoVehiculo_($input);
            return $this->sendResponse($historico, __('messages.app.data_retrieved', ['model' => $this->historicoVehiculoModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->historicoVehiculoModel->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Display a listing of the MTHistoricoVehiculo  whit pagination.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $historico = $this->historicoVehiculoRepository->listHistoricoVehiculo_paginate();
            return $this->sendResponse($historico, __('messages.app.data_retrieved', ['model' => $this->historicoVehiculoModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->historicoVehiculoModel->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTHistoricoVehiculo in storage.
     *
     * @param CreateMTHitoricoVehiculoAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $historico = $this->historicoVehiculoRepository->create($input);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->historicoVehiculoModel->model, json_encode($historico));
            return $this->sendResponse($historico->toArray(), __('messages.app.model_success', ['model' => $this->historicoVehiculoModel->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->historicoVehiculoModel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTHistoricoVehiculo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ModelsMTHistoricoVehiculo $historico */
        $historico = $this->historicoVehiculoRepository->find($id);

        if (empty($historico)) {
            return $this->sendError('Datos no encontrados');
        }

        return $this->sendResponse($historico->toArray(), 'Datos obtenidos satisfactoriamente');
    }

    /**
     * Update the specified MTHistoricoVehiculo in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $input = $request->all();

            /** @var ModelsMTHistoricoVehiculo $historico */
            $objActualizar = $historico = $this->historicoVehiculoRepository->find($id);

            if (empty($historico)) {
                return $this->sendError('Dato no encontrado');
            }

            $historico = $this->historicoVehiculoRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $historico];

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->historicoVehiculoModel->model, json_encode($objArray));
            return $this->sendResponse($historico->toArray(), __('messages.app.model_success', ['model' => $this->historicoVehiculoModel->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->historicoVehiculoModel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTHistoricoVehiculo from storage.
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var ModelsMTHistoricoVehiculo $historico */
            $historico = $this->historicoVehiculoModel->find($id);

            if (empty($historico)) {
                return $this->sendError('Dato no encontrado');
            }

            $historico->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->historicoVehiculoModel->model, json_encode($historico));
            return $this->sendSuccess('Dato eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->historicoVehiculoModel->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function historicoAccidentes(Request $request)
    {
        try {
            $historico = $this->historicoVehiculoRepository->getHistorico_($request);
            return $this->sendResponse($historico, __('messages.app.data_retrieved', ['model' => $this->historicoVehiculoModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->historicoVehiculoModel->model]), $exception->getMessage(), '500');
        }
    }
}
