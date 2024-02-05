<?php

namespace App\Http\Controllers;

use App\Models\MTCostoConformidad;
use App\Http\Resources\MTCostoNoConformmidadResource;
use App\Models\MTCostoNoConformidad;
use App\Models\Traza;
use App\Repositories\MTCostoConformidadRepository;
use App\Repositories\MTCostoNoConformidadRepository;
use App\Http\Requests\API\CreateCostoConformidadAPIRequest;
use App\Http\Requests\API\UpdateCostoConformidadAPIRequest;
use Illuminate\Http\Request;
use App\Utils\EventsUtil;

class MTCostoConformmidadController extends AppBaseController
{
    /** @var  MTCostoConformidadRepository */
    private $costoConformidadRepository;

    /** @var  MTCostoNoConformidadRepository */
    private $costoNoConformidadRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTCostoConformidad */
    private $costoConformidadModel;

    /** @var  MTCostoNoConformidad */
    private $costoNoConformidadModel;

    public function __construct(MTCostoConformidadRepository $costoConformidadRepository, MTCostoNoConformidadRepository $costoNoConformidadRepository, MTCostoConformidad $costoConformidadModel, MTCostoNoConformidad $costoNoConformidadModel, Traza $traza)
    {
        $this->costoConformidadRepository = $costoConformidadRepository;
        $this->costoNoConformidadRepository = $costoNoConformidadRepository;
        $this->costoConformidadModel = $costoConformidadModel;
        $this->costoNoConformidadModel = $costoNoConformidadModel;
        $this->traza = $traza;
    }

    /**
     * Display a listing of the CostoConformidad.
     * @param Request $request
     * @return Responsee
     */
    public function index(Request $request)
    {
        try {
            $costo = $this->costoConformidadRepository->listCostoConformidad($request);
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoConformidadModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoConformidadModel->model]), $exception->getMessage(), '500');
        }
    }
    /**
     * Display a listing of the CostoNoConformidad.
     * @param Request $request
     * @return Responsee
     */
    public function indexNoConformidad(Request $request)
    {
        try {
            $costo_noconformidad = $this->costoNoConformidadRepository->listCostoNoConformidad($request);
            return $this->sendResponse($costo_noconformidad, __('messages.app.data_retrieved', ['model' => $this->costoNoConformidadModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoNoConformidadModel->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created CostoConformidad in storage.
     *
     * @param CreateCostoConformidadAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->tipo === 'Conformidad') {
            try {
                $input = $request->all();
                $costo_conformidad = $this->costoConformidadRepository->create($input);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->costoConformidadModel->model, json_encode($costo_conformidad));
                return $this->sendResponse($costo_conformidad->toArray(), __('messages.app.model_success', ['model' => $this->costoConformidadModel->model, 'operation' => 'creada']));
            } catch (\Exception $exception) {
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoConformidadModel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
            }
        } else {
            try {
                $input = $request->all();
                $costo_noconformidad = $this->costoNoConformidadRepository->create($input);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->costoNoConformidadModel->model, json_encode($costo_noconformidad));
                return $this->sendResponse($costo_noconformidad->toArray(), __('messages.app.model_success', ['model' => $this->costoNoConformidadModel->model, 'operation' => 'creada']));
            } catch (\Exception $exception) {
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoNoConformidadModel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
            }
        }
    }

    /**
     * Display the specified CostoCalidadReport.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTCostoConformidad $costo_conformidad */
        $costo_conformidad = $this->costoConformidadRepository->find($id);

        if (empty($costo_conformidad)) {
            return $this->sendError('Data not found');
        }

        return $this->sendResponse($costo_conformidad->toArray(), 'Data retrieved successfully');
    }

    /**
     * Update the specified CostoConformidad in storage.
     *
     * @param int $id
     * @param UpdateCostoConformidadAPIRequest $request
     *
     * @return Response
     */
    public function update(UpdateCostoConformidadAPIRequest $request, $id)
    {

        if ($request->data['tipo'] === 'Conformidad') {
            try {
                $input = $request->all();

                /** @var MTCostoConformidad $costo_conformidad */
                $objActualizar = $costo_conformidad = $this->costoConformidadRepository->find($id);

                if (empty($costo_conformidad)) {
                    return $this->sendError('Data not found');
                }

                $costo_conformidad = $this->costoConformidadRepository->update($input, $id);
                $objArray = ['actualizar' => $objActualizar, 'actualizado' => $costo_conformidad];

                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->costoConformidadModel->model, json_encode($objArray));
                return $this->sendResponse($costo_conformidad->toArray(), __('messages.app.model_success', ['model' => $this->costoConformidadModel->model, 'operation' => 'actualizada']));
            } catch (\Exception $exception) {
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoConformidadModel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
            }
        } else {
            try {
                $input = $request->all();

                /** @var MTCostoNoConformidad $costo_noconformidad */
                $objActualizar = $costo_noconformidad = $this->costoNoConformidadRepository->find($id);

                if (empty($costo_noconformidad)) {
                    return $this->sendError('Data not found');
                }

                $costo_noconformidad = $this->costoNoConformidadRepository->update($input, $id);
                $objArray = ['actualizar' => $objActualizar, 'actualizado' => $costo_noconformidad];

                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->costoNoConformidadModel->model, json_encode($objArray));
                return $this->sendResponse($costo_noconformidad->toArray(), __('messages.app.model_success', ['model' => $this->costoNoConformidadModel->model, 'operation' => 'actualizada']));
            } catch (\Exception $exception) {
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoNoConformidadModel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
            }
        }
    }

    /**
     * Remove the specified CostoConformidad from storage.
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTCostoConformidad $costo_conformidad */
            $costo_conformidad = $this->costoConformidadRepository->find($id);

            if (empty($costo_conformidad)) {
                return $this->sendError('Data not found');
            }

            $costo_conformidad->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->costoConformidadModel->model, json_encode($costo_conformidad));
            return $this->sendSuccess('Data deleted successfully');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->costoConformidadModel->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }
}
