<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMTDemandaAPIRequest;
use App\Http\Requests\API\UpdateMTDemandaAPIRequest;
use App\Models\MTDemanda;
use App\Models\Traza;
use App\Repositories\MTDemandaRepository;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MTDemandaResource;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTDemandaController
 * @package App\Http\Controllers\API
 */
class MTDemandaAPIController extends AppBaseController
{
    /** @var  MTDemandaRepository */
    private $mTDemandaRepository;

    /** @var  MTDemanda */
    private $demanda;

    /** @var  Traza */
    private $traza;

    public function __construct(MTDemandaRepository $mTDemandaRepo, Traza $traza, MTDemanda $demanda)
    {
        $this->mTDemandaRepository = $mTDemandaRepo;
        $this->traza = $traza;
        $this->demanda = $demanda;
    }

    /**
     * Display a listing of the MTDemanda.
     * GET|HEAD /mTDemandas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $demandas = $this->mTDemandaRepository->getAllPaginateDemanda($request);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->demanda->model]));
            return $this->sendResponse($demandas->toArray(), __('messages.app.data_retrieved', ['model' => $this->demanda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->demanda->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTDemanda in storage.
     * POST /mTDemandas
     *
     * @param CreateMTDemandaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTDemandaAPIRequest $request)
    {
        try {
            $input = $request->all();
            $datosGenerales = $input;
            for ($i = 0; $i < count($datosGenerales['data']); $i++) {
                if (count($datosGenerales['data']) > 0) {
                    $mTDemanda = MTDemanda::create([
                        'instalacion_id' => $this->mTDemandaRepository->getInstalacionId($datosGenerales['datosGenerales']),
                        'familia_id' => $datosGenerales['datosGenerales']['familia_id'],
                        'producto_id' => $this->mTDemandaRepository->getProductoId($datosGenerales['data'][$i]),
                        'unidad_medida' => $datosGenerales['data'][$i]['unidad_medida'],
                        'enero' => $datosGenerales['data'][$i]['enero'],
                        'febrero' => $datosGenerales['data'][$i]['febrero'],
                        'marzo' => $datosGenerales['data'][$i]['marzo'],
                        'abril' => $datosGenerales['data'][$i]['abril'],
                        'mayo' => $datosGenerales['data'][$i]['mayo'],
                        'junio' => $datosGenerales['data'][$i]['junio'],
                        'julio' => $datosGenerales['data'][$i]['julio'],
                        'agosto' => $datosGenerales['data'][$i]['agosto'],
                        'septiembre' => $datosGenerales['data'][$i]['septiembre'],
                        'octubre' => $datosGenerales['data'][$i]['octubre'],
                        'noviembre' => $datosGenerales['data'][$i]['noviembre'],
                        'diciembre' => $datosGenerales['data'][$i]['diciembre'],
                        'anno' => $datosGenerales['datosGenerales']['anno'],
                        'total_demanda_anual' => $datosGenerales['data'][$i]['total_demanda_anual'],
                    ]);
                }
            }
            Log::info(__('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'creada']) . ' con id = ' . $mTDemanda->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->demanda->model, json_encode($mTDemanda));
            return $this->sendResponse($mTDemanda->toArray(), __('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->demanda->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTDemanda.
     * GET|HEAD /mTDemandas/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTDemanda $mTDemanda */
        $mTDemanda = $this->mTDemandaRepository->find($id);

        if (empty($mTDemanda)) {
            return $this->sendError('M T Demanda not found');
        }

        return $this->sendResponse(new MTDemandaResource($mTDemanda), 'M T Demanda retrieved successfully');
    }

    /**
     * Update the specified MTDemanda in storage.
     * PUT/PATCH /mTDemandas/{id}
     *
     * @param int $id
     * @param UpdateMTDemandaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTDemandaAPIRequest $request)
    {
        try {
            $input = $request->all();
            $datosGenerales = $input;
            /** @var MTDemanda $mTDemanda */
            $objActualizar = $mTDemanda = $this->mTDemandaRepository->findIDAnno($id, $datosGenerales['datosGenerales']['anno']);
            $this->mTDemandaRepository->eliminarDemanda($id, $datosGenerales['datosGenerales']['anno']);

            if (empty($mTDemanda)) {
                return $this->sendError('M T Demanda not found');
            }
            for ($i = 0; $i < count($datosGenerales['data']); $i++) {
                if (count($datosGenerales['data']) > 0) {
                    $mTDemanda = MTDemanda::create([
                        'instalacion_id' => $this->mTDemandaRepository->getInstalacionId($datosGenerales['datosGenerales']),
                        'familia_id' => $datosGenerales['datosGenerales']['familia_id'],
                        'producto_id' => $this->mTDemandaRepository->getProductoId($datosGenerales['data'][$i]),
                        'unidad_medida' => $datosGenerales['data'][$i]['unidad_medida'],
                        'enero' => $datosGenerales['data'][$i]['enero'],
                        'febrero' => $datosGenerales['data'][$i]['febrero'],
                        'marzo' => $datosGenerales['data'][$i]['marzo'],
                        'abril' => $datosGenerales['data'][$i]['abril'],
                        'mayo' => $datosGenerales['data'][$i]['mayo'],
                        'junio' => $datosGenerales['data'][$i]['junio'],
                        'julio' => $datosGenerales['data'][$i]['julio'],
                        'agosto' => $datosGenerales['data'][$i]['agosto'],
                        'septiembre' => $datosGenerales['data'][$i]['septiembre'],
                        'octubre' => $datosGenerales['data'][$i]['octubre'],
                        'noviembre' => $datosGenerales['data'][$i]['noviembre'],
                        'diciembre' => $datosGenerales['data'][$i]['diciembre'],
                        'anno' => $datosGenerales['datosGenerales']['anno'],
                        'total_demanda_anual' => $datosGenerales['data'][$i]['total_demanda_anual'],
                    ]);
                }
            }
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTDemanda];
            Log::info(__('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'actualizada']) . ' con id = ' . $mTDemanda->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->demanda->model, json_encode($objArray));
            return $this->sendResponse($mTDemanda->toArray(), __('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->demanda->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTDemanda from storage.
     * DELETE /mTDemandas/{id}
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function eliminarDemanda(Request $request)
    {
        try {
            $input = $request->all();
            $datosGenerales = $input;
            /** @var MTDemanda $mTDemanda */
            $mTDemanda = $this->mTDemandaRepository->findIDAnno($datosGenerales['datosGenerales'][0]['instalacion_id'], $datosGenerales['datosGenerales'][0]['anno']);
            $this->mTDemandaRepository->eliminarDemanda($datosGenerales['datosGenerales'][0]['instalacion_id'], $datosGenerales['datosGenerales'][0]['anno']);

            if (empty($mTDemanda)) {
                return $this->sendError('M T Demanda not found');
            }

            Log::info(__('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'eliminada']) . ' con id = ' . $datosGenerales['datosGenerales'][0]['instalacion_id']);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->demanda->model, json_encode($mTDemanda));
            return $this->sendSuccess('Persona deleted successfully');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->demanda->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function get_demandas(Request $request)
    {
        try {
            $demandas = $this->mTDemandaRepository->getDemandas();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->demanda->model]));
            return $this->sendResponse($demandas->toArray(), __('messages.app.data_retrieved', ['model' => $this->demanda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->demanda->model]), $exception->getMessage(), '500');
        }
    }

    public function procesarDemanda(Request $request)
    {
        try {
            $input = $request->all();
            $datosGenerales = $input;
            /** @var MTDemanda $mTDemanda */
            $objActualizar = $mTDemanda = $this->mTDemandaRepository->findIDAnno($datosGenerales['datosGenerales']['instalacion_id'], $datosGenerales['datosGenerales']['anno']);
            $this->mTDemandaRepository->eliminarDemanda($datosGenerales['datosGenerales']['instalacion_id'], $datosGenerales['datosGenerales']['anno']);

            if (empty($mTDemanda)) {
                return $this->sendError('M T Demanda not found');
            }
            for ($i = 0; $i < count($datosGenerales['data']); $i++) {
                if (count($datosGenerales['data']) > 0) {
                    $mTDemanda = MTDemanda::create([
                        'instalacion_id' => $this->mTDemandaRepository->getInstalacionId($datosGenerales['datosGenerales']),
                        'familia_id' => $datosGenerales['datosGenerales']['familia_id'],
                        'producto_id' => $this->mTDemandaRepository->getProductoId($datosGenerales['data'][$i]),
                        'unidad_medida' => $datosGenerales['data'][$i]['unidad_medida'],
                        'enero' => $datosGenerales['data'][$i]['enero'],
                        'febrero' => $datosGenerales['data'][$i]['febrero'],
                        'marzo' => $datosGenerales['data'][$i]['marzo'],
                        'abril' => $datosGenerales['data'][$i]['abril'],
                        'mayo' => $datosGenerales['data'][$i]['mayo'],
                        'junio' => $datosGenerales['data'][$i]['junio'],
                        'julio' => $datosGenerales['data'][$i]['julio'],
                        'agosto' => $datosGenerales['data'][$i]['agosto'],
                        'septiembre' => $datosGenerales['data'][$i]['septiembre'],
                        'octubre' => $datosGenerales['data'][$i]['octubre'],
                        'noviembre' => $datosGenerales['data'][$i]['noviembre'],
                        'diciembre' => $datosGenerales['data'][$i]['diciembre'],
                        'anno' => $datosGenerales['datosGenerales']['anno'],
                        'total_demanda_anual' => $datosGenerales['data'][$i]['total_demanda_anual'],
                        'aprobado' => $datosGenerales['data'][$i]['aprobado'],
                        'deficit' => $datosGenerales['data'][$i]['deficit'],
                        'porciento_aprobado' => $datosGenerales['data'][$i]['porciento_aprobado'],
                    ]);
                }
            }
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mTDemanda];
            Log::info(__('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'actualizada']) . ' con id = ' . $mTDemanda->id);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 'Aprovar demanda', EventsUtil::getUserId(), $this->demanda->model, json_encode($objArray));
            return $this->sendResponse($mTDemanda->toArray(), __('messages.app.model_success', ['model' => $this->demanda->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.model_error', ['model' => $this->demanda->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }
    public function get_demandas_instalacion_anno(Request $request)
    {
        try {
            $demandas = $this->mTDemandaRepository->getDemandasInstalacionAnno($request['id'], $request['anno']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->demanda->model]));
            return $this->sendResponse($demandas->toArray(), __('messages.app.data_retrieved', ['model' => $this->demanda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->demanda->model]), $exception->getMessage(), '500');
        }
    }
    public function get_demandas_instalacion_ran_anno(Request $request)
    {
        try {
            $demandas = $this->mTDemandaRepository->getDemandasInstalacionRanAnnos($request['id'], $request['annoInicial'], $request['annoFinal']);
            Log::info(__('messages.app.data_retrieved', ['model' => $this->demanda->model]));
            return $this->sendResponse($demandas->toArray(), __('messages.app.data_retrieved', ['model' => $this->demanda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->demanda->model]), $exception->getMessage(), '500');
        }
    }
    public function get_annos()
    {
        try {
            $demandas = $this->mTDemandaRepository->getAnnos();
            Log::info(__('messages.app.data_retrieved', ['model' => $this->demanda->model]));
            return $this->sendResponse($demandas->toArray(), __('messages.app.data_retrieved', ['model' => $this->demanda->model]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->demanda->model]), $exception->getMessage(), '500');
        }
    }
    public function dashboardInfo(Request $request)
    {
        try {
            $data = $this->mTDemandaRepository->demandaAprobada($request);
            return $this->sendResponse($data->toArray(), __('messages.app.data_retrieved', ['model' => $this->demanda->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->demanda->model]), $exception->getMessage(), '500');
        }
    }
}
