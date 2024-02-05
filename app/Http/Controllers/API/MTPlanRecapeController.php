<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateMTPlanRecapeAPIRequest;
use App\Http\Requests\API\UpdateMTPlanRecapeAPIRequest;
use App\Mail\PlanRecapeVencido;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Models\MTPlanRecape as ModelMTPlanRecape;
use App\Models\Traza;
use App\Repositories\MTPlanRecapeRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class MTPlanRecapeController extends AppBaseController
{
    /** @var ModelMTPlanRecape */
    private $planRecapeModel;

    /** @var MTPlanRecapeRepository */
    private $planrecapeRepository;

    /** @var Traza */
    private $traza;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(ModelMTPlanRecape $planRecapeModel, Traza $traza, MTPlanRecapeRepository $planrecapeRepository, UserRepository $userRepository)
    {
        $this->planRecapeModel = $planRecapeModel;
        $this->traza = $traza;
        $this->planrecapeRepository = $planrecapeRepository;
        $this->userRepository = $userRepository;
    }

    public function listadoPlanRecape()
    {
        try {
            $user = Auth::user();
            $planrecape = $this->planrecapeRepository->listPlanRecape_($user->instalacion_id);
            return $this->sendResponse($planrecape, __('messages.app.data_retrieved', ['model' => $this->planRecapeModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planRecapeModel->model]), $exception->getMessage(), '500');
        }
    }
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $input = $request->all();
            $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
            $planrecape = $this->planrecapeRepository->listPlanRecape_paginate($input);
            return $this->sendResponse($planrecape, __('messages.app.data_retrieved', ['model' => $this->planRecapeModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planRecapeModel->model]), $exception->getMessage(), '500');
        }
    }

    public function store(CreateMTPlanRecapeAPIRequest $request)
    {
        try {
            if (now()->month >= 6) { // validar que sea el segundo semestre
                $user = $request->user();
                $input = $request->all();
                $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
                $input = Arr::add($input, 'fecha', now());
                $input = Arr::add($input, 'anno', now()->addYear()->format('Y'));
                $planrecape = $this->planrecapeRepository->create($input);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->planRecapeModel->model, json_encode($planrecape));
                return $this->sendResponse($planrecape->toArray(), __('messages.app.model_success', ['model' => $this->planRecapeModel->model, 'operation' => 'creada']));
            } else {
                return $this->sendError('Solo se puede crear un plan en el segundo semestre', 'Período incorrecto');
            }
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->planRecapeModel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    public function show($id)
    {
        $planrecape = $this->planrecapeRepository->find($id);

        if (empty($planrecape)) {
            return $this->sendError('Datos no encontrados');
        }

        return $this->sendResponse($planrecape->toArray(), 'Datos obtenidos satisfactoriamente');
    }

    public function update($id, UpdateMTPlanRecapeAPIRequest $request)
    {
        try {
            $input = $request->except('instalaciones');

            $objActualizar = $planrecape = $this->planrecapeRepository->find($id);

            if (empty($planrecape)) {
                return $this->sendError('Dato no encontrado');
            }

            $planrecape = $this->planrecapeRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $planrecape];

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->planRecapeModel->model, json_encode($objArray));
            return $this->sendResponse($planrecape->toArray(), __('messages.app.model_success', ['model' => $this->planRecapeModel->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->planRecapeModel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $planrecape = $this->planrecapeRepository->find($id);

            if (empty($planrecape)) {
                return $this->sendError('Dato no encontrado');
            }

            $planrecape->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->planRecapeModel->model, json_encode($planrecape));
            return $this->sendSuccess('Dato eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->planRecapeModel->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    //Dashboard
    public function cumplimientoPlanRecapeInst_dashboard()
    {
        try {
            $dashboard = $this->planrecapeRepository->planRecapeOsde_dashboard();
            return $this->sendResponse($dashboard, __('messages.app.data_retrieved', ['model' => $this->planRecapeModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planRecapeModel->model]), $exception->getMessage(), '500');
        }
    }
    public function cumplimientoPlanRecapeTotal_dashboard()
    {
        try {
            $dashboard = $this->planrecapeRepository->planRecapeTotal_dashboard();
            return $this->sendResponse($dashboard, __('messages.app.data_retrieved', ['model' => $this->planRecapeModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planRecapeModel->model]), $exception->getMessage(), '500');
        }
    }
    //Comprueba si existe un plan de recape vigente
    public function comprobarPlanRecape()
    {
        try {
            $response = $this->planrecapeRepository->comprobarExistenciaPlanRecape();
            if ($response['status'] === 'vencido') {
                $emails = $this->userRepository->listUserEmailByPermission('gestionar_dir_transporte_fi', $response['instlacion_id']);
                Mail::to($emails)->send(new PlanRecapeVencido());
            }
            return $response;
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planRecapeModel->model]), $exception->getMessage(), '500');
        }
    }
}
