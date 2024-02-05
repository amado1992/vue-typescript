<?php

namespace App\Http\Controllers;

use App\Http\Requests\API\CreateMTCostoCalidadConformidadAPIRequest;
use App\Http\Requests\API\UpdateMTCostoCalidadConformidadAPIRequest;
use App\Http\Resources\MTCostoCalidadConformidadResource;
use App\Models\MTCostoCalidadConformidad;
use App\Http\Resources\MTCostoCalidadNoConformidadResource;
use App\Models\MTCostoCalidadNoConformidad;
use App\Repositories\MTCostoCalidadConformidadRepository;
use App\Repositories\MTCostoCalidadNoConformidadRepository;
use App\Models\Traza;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use App\Utils\EventsUtil;

class MTCostoCalidadConformidadController extends AppBaseController
{
    /** @var  MTCostoCalidadConformidadRepository */
    private $costoCalidadlRepository;

    /** @var  MTCostoCalidadNoConformidadRepository */
    private $costoCalidadlNoConfRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTCostoCalidadConformidad */
    private $costoCalidad;

    /** @var  MTCostoCalidadNoConformidad */
    private $costoCalidadNoConf;

    public function __construct(MTCostoCalidadConformidadRepository $costoCalidadlRepository, MTCostoCalidadNoConformidadRepository $costoCalidadlNoConfRepository, MTCostoCalidadConformidad $costoCalidad, MTCostoCalidadNoConformidad $costoCalidadNoConf, Traza $traza)
    {
        $this->costoCalidadlRepository = $costoCalidadlRepository;
        $this->costoCalidadlNoConfRepository = $costoCalidadlNoConfRepository;
        $this->traza = $traza;
        $this->costoCalidad = $costoCalidad;
        $this->costoCalidadNoConf = $costoCalidadNoConf;
    }

    /**
     * Display a listing of the CostoCalidadConformidad.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $costo = $this->costoCalidadlRepository->listCostoCalidadConformidad($request);
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoCalidad->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidad->model]), $exception->getMessage(), '500');
        }
    }
    /**
     * Display a listing of the CostoCalidadNoConformidad.
     *
     * @param Request $request
     * @return Response
     */
    public function indexNoConformidad(Request $request)
    {
        try {
            $costo = $this->costoCalidadlNoConfRepository->listCostoCalidadNoConformidad($request);
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoCalidadNoConf->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidadNoConf->model]), $exception->getMessage(), '500');
        }
    }
    /**
     * Store a newly created CostoCalidadNoConformidad in storage.
     *
     * @param CreateMTCostoCalidadConformidadAPIRequest $request
     * @return Response
     */
    public function store(Request $request)
   // public function store(CreateMTCostoCalidadConformidadAPIRequest $request)
    {
        //Validate number negative
        if (!is_numeric($request->importe) || !is_numeric($request->acumulado)) {
            return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
        }

        if (is_numeric($request->importe) || is_numeric($request->acumulado)){
            if ($request->importe < 1 || $request->acumulado < 1){
                return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
            }
        }
        //End validate number negative

        if ($request->tipo === 'Calidad Conformidad') {
            try {
                $input = $request->all();
                $costo = $this->costoCalidadlRepository->create($input);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->costoCalidad->model, json_encode($costo));
                return $this->sendResponse($costo->toArray(), __('messages.app.model_success', ['model' => $this->costoCalidad->model, 'operation' => 'creada']));
            } catch (\Exception $exception) {
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidad->model, 'operation' => 'creada']), $exception->getMessage(), '500');
            }
        } else {
            try {
                $input = $request->all();
                $costoNoConf = $this->costoCalidadlNoConfRepository->create($input);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->costoCalidadNoConf->model, json_encode($costoNoConf));
                return $this->sendResponse($costoNoConf->toArray(), __('messages.app.model_success', ['model' => $this->costoCalidadNoConf->model, 'operation' => 'creada']));
            } catch (\Exception $exception) {
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidadNoConf->model, 'operation' => 'creada']), $exception->getMessage(), '500');
            }
        }
    }

    /**
     * Display the specified CostoCalidadConformidad.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTCostoCalidadConformidad $costo */
        $costo = $this->indicadoreslRepository->find($id);

        if (empty($costo)) {
            return $this->sendError('Costo no encontrado');
        }

        return $this->sendResponse($costo->toArray(), 'Datos obtenidos satisfactoriamente');
    }

    /**
     * Update the specified CostoCalidadConformidad in storage.
     *
     * @param int $id
     *
     * @param UpdateMTCostoCalidadConformidadAPIRequest $request
     * @return Response
     */
    public function update(UpdateMTCostoCalidadConformidadAPIRequest $request, $id) // implementar seguridad
    {
        //Validate number negative
        if (!is_numeric($request->importe) || !is_numeric($request->acumulado)) {
            return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
        }

        if (is_numeric($request->importe) || is_numeric($request->acumulado)){
            if ($request->importe < 1 || $request->acumulado < 1){
                return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
            }
        }
        //End validate number negative

        if ($request->data['tipo'] === 'Calidad Conformidad') {
            try {
                $input = $request->all();

                /** @var MTCostoCalidadConformidad $costo */
                $objActualizar = $costo = $this->costoCalidadlRepository->find($id);

                if (empty($costo)) {
                    return $this->sendError('Costo no encontrado');
                }

                $costo = $this->costoCalidadlRepository->update($input, $id);
                $objArray = ['actualizar' => $objActualizar, 'actualizado' => $costo];

                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->costoCalidad->model, json_encode($objArray));
                return $this->sendResponse($costo->toArray(), __('messages.app.model_success', ['model' => $this->costoCalidad->model, 'operation' => 'actualizada']));
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidad->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
            }
        } else {
            try {
                $input = $request->all();

                /** @var MTCostoCalidadNoConformidad $noconformidad */
                $objActualizar = $noconformidad = $this->costoCalidadlNoConfRepository->find($id);

                if (empty($noconformidad)) {
                    return $this->sendError('Costo no encontrado');
                }

                $noconformidad = $this->costoCalidadlNoConfRepository->update($input, $id);
                $objArray = ['actualizar' => $objActualizar, 'actualizado' => $noconformidad];

                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->costoCalidadNoConf->model, json_encode($objArray));
                return $this->sendResponse($noconformidad->toArray(), __('messages.app.model_success', ['model' => $this->costoCalidadNoConf->model, 'operation' => 'actualizada']));
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidadNoConf->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->tipo === 'Calidad Conformidad') {
            try {
                /** @var MTCostoCalidadConformidad $costo */
                $costo = $this->costoCalidadlRepository->find($id);

                if (empty($costo)) {
                    return $this->sendError('Costo no encontrado');
                }

                $costo->delete();

                Log::info(__('messages.app.model_success', ['model' => $this->costoCalidad->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->costoCalidad->model, json_encode($costo));
                return $this->sendSuccess('Costo eliminado satisfactoriamente');
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidad->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
            }
        } else {
            try {
                /** @var MTCostoCalidadNoConformidad $noconformidad */
                $noconformidad = $this->costoCalidadlNoConfRepository->find($id);

                if (empty($noconformidad)) {
                    return $this->sendError('Costo no encontrado');
                }

                $noconformidad->delete();

                Log::info(__('messages.app.model_success', ['model' => $this->costoCalidadNoConf->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
                $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->costoCalidadNoConf->model, json_encode($noconformidad));
                return $this->sendSuccess('Costo eliminado satisfactoriamente');
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidadNoConf->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
            }
        }
    }

    /** REPORTES */
    public function entities_all_costs()
    {
        try {
            $costo = $this->costoCalidadlRepository->entities_all_costs();
            return $costo;
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidad->model]), $exception->getMessage(), '500');
        }
    }
    public function entities_all_indicators()
    {
        try {
            $costo = $this->costoCalidadlRepository->entities_all_indicators();
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoCalidad->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidad->model]), $exception->getMessage(), '500');
        }
    }
    public function entities_all_costs_trimester(Request $request)
    {
        try {
            $costo = $this->costoCalidadlRepository->entities_all_costs_trimester($request);
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoCalidad->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidad->model]), $exception->getMessage(), '500');
        }
    }
    public function entity_all_costs_specify(Request $request)
    {
        try {
            $costo = $this->costoCalidadlRepository->entity_all_costs_specify($request);
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoCalidad->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidad->model]), $exception->getMessage(), '500');
        }
    }
    public function entity_all_costs_total(Request $request)
    {
        try {
            $costo = $this->costoCalidadlRepository->entity_all_costs_total($request);
            return $this->sendResponse($costo, __('messages.app.data_retrieved', ['model' => $this->costoCalidad->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidad->model]), $exception->getMessage(), '500');
        }
    }
}
