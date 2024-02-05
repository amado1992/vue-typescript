<?php

namespace App\Http\Controllers;

use App\Models\MTCostoCalidadReport;
use App\Repositories\MTCostoCalidadReportRepository;
use App\Models\Traza;
use App\Http\Requests\API\CreateCostoCalidadReportAPIRequest;
use App\Http\Requests\API\UpdateCostoCalidadReportAPIRequest;
use App\Utils\EventsUtil;
use Illuminate\Http\Request;

class MTCostoCalidadReportController extends AppBaseController
{
    /** @var  MTCostoCalidadReportRepository */
    private $costoCalidadReportRepository;

    /** @var  Traza */
    private $traza;

    /** @var  MTCostoCalidadReport */
    private $costoCalidadReport;

    public function __construct(MTCostoCalidadReportRepository $costoCalidadReportRepository, MTCostoCalidadReport $costoCalidadReport, Traza $traza)
    {
        $this->costoCalidadReportRepository = $costoCalidadReportRepository;
        $this->traza = $traza;
        $this->costoCalidadReport = $costoCalidadReport;
    }


    /**
     * Display a listing of the CostoCalidadReport.
     * @param Request $request
     * @return Responsee
     */
    public function index(Request $request)
    {
        try {
            $costo_calidad_reports = $this->costoCalidadReportRepository->listCostoCalidadReport($request);
            return $this->sendResponse($costo_calidad_reports, __('messages.app.data_retrieved', ['model' => $this->costoCalidadReport->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidadReport->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created CostoCalidadReport in storage.
     *
     * @param CreateCostoCalidadReportAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCostoCalidadReportAPIRequest $request)
    { //Validate number negative
        if (!is_numeric($request->ventaNetaAcumulada) || !is_numeric($request->ventaNetaImporte)) {
            return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
        }

        if (is_numeric($request->ventaNetaAcumulada	) || is_numeric($request->ventaNetaImporte)){
            if ($request->ventaNetaAcumulada < 1 || $request->ventaNetaImporte < 1){
                return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
            }
        }
        //End validate number negative

        try {
            $input = $request->all();
            $costo_calidad_reports = $this->costoCalidadReportRepository->create($input);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->costoCalidadReport->model, json_encode($costo_calidad_reports));
            return $this->sendResponse($costo_calidad_reports->toArray(), __('messages.app.model_success', ['model' => $this->costoCalidadReport->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidadReport->model, 'operation' => 'creada']), $exception->getMessage(), '500');
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
        /** @var MTCostoCalidadReport $costo */
        $costo = $this->costoCalidadReportRepository->find($id);

        if (empty($costo)) {
            return $this->sendError('Data not found');
        }

        return $this->sendResponse($costo->toArray(), 'Data retrieved successfully');
    }

    /**
     * Update the specified CostoCalidadReport in storage.
     *
     * @param int $id
     * @param UpdateCostoCalidadReportAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCostoCalidadReportAPIRequest $request)
    {
        //Validate number negative
        if (!is_numeric($request->ventaNetaAcumulada) || !is_numeric($request->ventaNetaImporte)) {
            return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
        }

        if (is_numeric($request->ventaNetaAcumulada	) || is_numeric($request->ventaNetaImporte)){
            if ($request->ventaNetaAcumulada < 1 || $request->ventaNetaImporte < 1){
                return response()->json(['status_code' => 200, 'message' => 'No se permite número negativo']);
            }
        }
        //End validate number negative

        try {
            $input = $request->all();

            /** @var MTCostoCalidadReport $costo_calidad_report */
            $objActualizar = $costo_calidad_report = $this->costoCalidadReportRepository->find($id);

            if (empty($costo_calidad_report)) {
                return $this->sendError('Data not found');
            }

            $costo_calidad_report = $this->costoCalidadReportRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $costo_calidad_report];

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->costoCalidadReport->model, json_encode($objArray));
            return $this->sendResponse($costo_calidad_report->toArray(), __('messages.app.model_success', ['model' => $this->costoCalidadReport->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidadReport->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified CostoCalidadReport from storage.
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var MTCostoCalidadReport $costo_calidad_report */
            $costo_calidad_report = $this->costoCalidadReportRepository->find($id);

            if (empty($costo_calidad_report)) {
                return $this->sendError('Data not found');
            }

            $costo_calidad_report->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->costoCalidadReport->model, json_encode($costo_calidad_report));
            return $this->sendSuccess('data deleted successfully');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->costoCalidadReport->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    /** DASHBOARD */
    public function totalReportesXanno(Request $request)
    {
        try {
            return $this->costoCalidadReportRepository->totalReportesXanno($request);
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->costoCalidadReport->model]), $exception->getMessage(), '500');
        }
    }
}
