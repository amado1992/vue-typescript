<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Utils\EventsUtil;
use App\Models\MTMedioTransporte as ModelsMTMedioTransporte;
use App\Models\Traza;
use App\Repositories\MTMedioTransporteRepository;
use App\Repositories\MTHistoricoVehiculoRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\API\CreateMTmedioTransporteAPIRequest;
use App\Http\Requests\API\UpdateMTmedioTransporteAPIRequest;
use App\Models\MTHistoricoVehiculo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\VehiculoFICAVporVencer;
use stdClass;

class MTMedioTransporteController extends AppBaseController
{
    /** @var MTMedioTransporteRepository */
    private $medioTransporteRepository;

    /** @var MTHistoricoVehiculoRepository */
    private $historicoVehiculoRepository;

    /** @var ModelsMTMedioTransporte */
    private $medioTransporteModel;

    /** @var UserRepository */
    private $userRepository;

    /** @var Traza */
    private $traza;

    public function __construct(MTMedioTransporteRepository $medioTransporteRepository, MTHistoricoVehiculoRepository $historicoVehiculoRepository, ModelsMTMedioTransporte $medioTransporteModel, Traza $traza, UserRepository $userRepository)
    {
        $this->medioTransporteRepository = $medioTransporteRepository;
        $this->historicoVehiculoRepository = $historicoVehiculoRepository;
        $this->medioTransporteModel = $medioTransporteModel;
        $this->traza = $traza;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the MTMedioTransporte
     *
     * @param Request $request
     * @return Response
     */
    public function listMedioTransporte()
    {
        try {
            $user = Auth::user();
            $mediotransporte = $this->medioTransporteRepository->listMedioTransporte_($user->instalacion_id);
            return $this->sendResponse($mediotransporte, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Display a listing of the MTMedioTransporte  whit pagination.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $input = $request->all();
            $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
            $mediotransporte = $this->medioTransporteRepository->listMedioTransporte_paginate($input);
            return $this->sendResponse($mediotransporte, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    /**
     * Store a newly created MTMedioTransporte in storage.
     *
     * @param CreateMTmedioTransporteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMTmedioTransporteAPIRequest $request)
    {
        try {
            $user = $request->user();
            $input = $request->all();
            $tiempoArray = explode(' ', $this->tiempoTranscurrido(strtotime($input['fecha_ficav'])));
            $input = (intval($tiempoArray[0]) >= 2 && $tiempoArray[1] === 'años') ? Arr::set($input, 'situacion_actual', 'Paralizado') : Arr::set($input, 'situacion_actual', $input['situacion_actual']);
            $input = Arr::add($input, 'fecha', date('Y-m-d'));
            $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
            $input = Arr::add($input, 'edad', $this->calcularEdad($input['anno_fabricacion']));
            $input = Arr::add($input, 'capacidad_carga_um', $this->setCapacidadCargaUM($input['tipo_flota'], $input['tipo_medio_transporte']));
            $mediotransporte = $this->medioTransporteRepository->create($input);
            $this->saveHistorico('new', $mediotransporte, null);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->medioTransporteModel->model, json_encode($mediotransporte));
            return $this->sendResponse($mediotransporte->toArray(), __('messages.app.model_success', ['model' => $this->medioTransporteModel->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->medioTransporteModel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Display the specified MTMedioTransporte.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ModelsMTmedio_transporte $mediotransporte */
        $mediotransporte = $this->medioTransporteRepository->find($id);

        if (empty($mediotransporte)) {
            return $this->sendError('Datos no encontrados');
        }

        return $this->sendResponse($mediotransporte->toArray(), 'Datos obtenidos satisfactoriamente');
    }

    /**
     * Update the specified MTMedioTransporte in storage.
     *
     * @param int $id
     * @param UpdateMTmedioTransporteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMTmedioTransporteAPIRequest $request)
    {
        try {
            $input = $request->except('instalaciones');
            $tiempoArray = explode(' ', $this->tiempoTranscurrido(strtotime($input['fecha_ficav'])));
            $input = (intval($tiempoArray[0]) >= 2 && $tiempoArray[1] === 'años') ? Arr::set($input, 'situacion_actual', 'Paralizado') : Arr::set($input, 'situacion_actual', $input['situacion_actual']);
            $input = Arr::set($input, 'edad', $this->calcularEdad($input['anno_fabricacion']));
            $input = Arr::add($input, 'capacidad_carga_um', $this->setCapacidadCargaUM($input['tipo_flota'], $input['tipo_medio_transporte']));

            /** @var ModelsMTMedioTransporte $mediotransporte */
            $objActualizar = $mediotransporte = $this->medioTransporteRepository->find($id);

            if (empty($mediotransporte)) {
                return $this->sendError('Dato no encontrado');
            }
            if ($request['situacion_actual'] == ' Baja Técnica' || $request['situacion_actual'] == 'Baja Turística') {
                if ($this->pendienteBajaTecnica($id, 'Propuesto a Baja')) {
                    return response()->json(['alert' => true, 'data' => null, 'msg' => 'No puede poner en Baja Técnica el medio de transporte especificado']);
                }
            }

            $mediotransporte = $this->medioTransporteRepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $mediotransporte];

            $this->saveHistorico('update', $mediotransporte);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->medioTransporteModel->model, json_encode($objArray));
            return $this->sendResponse($mediotransporte->toArray(), __('messages.app.model_success', ['model' => $this->medioTransporteModel->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->medioTransporteModel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    /**
     * Remove the specified MTMedioTransporte from storage.
     *
     * @param int $id
     *
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        try {
            /** @var ModelsMTMedioTransporte $mediotransporte */
            $mediotransporte = $this->medioTransporteRepository->find($id);

            if (empty($mediotransporte)) {
                return $this->sendError('Dato no encontrado');
            }

            $mediotransporte->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->medioTransporteModel->model, json_encode($mediotransporte));
            return $this->sendSuccess('Dato eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->medioTransporteModel->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function calcularEdad($annoFabricacion)
    {
        $anno = date('Y');
        return $anno - $annoFabricacion;
    }

    public function saveHistorico($action, $data)
    {
        if ($action == 'new') {
            $lastModel = ModelsMTMedioTransporte::latest('id')->first();
            $historico = array();
            $historico['vehiculo_id'] = $lastModel->id;
            $historico['estado'] = $lastModel->situacion_actual;
            $historico['estado_fechaInicial'] = now();
            $this->historicoVehiculoRepository->create($historico);
        } else {
            $vehiculoId = $data->only('id');
            $estado = $data->only('situacion_actual');
            $historicoUpdate = MTHistoricoVehiculo::where('vehiculo_id', $vehiculoId)
                ->whereNull('estado_fechaFinal')
                ->get()
                ->first()
                ->toArray();
            $historicoUpdate = Arr::set($historicoUpdate, 'estado_fechaFinal', date('Y-m-d'));
            $this->historicoVehiculoRepository->update($historicoUpdate, $historicoUpdate['id']);
            $historicoUpdate = Arr::set($historicoUpdate, 'estado', $estado['situacion_actual']);
            $historicoUpdate = Arr::except($historicoUpdate, ['id', 'estado_fechaFinal', 'created_at', 'updated_at']);
            $this->historicoVehiculoRepository->create($historicoUpdate);
        }
    }

    /**
     * Calcula un estimado del tiempo transcurrido entre una fecha dada y la actual.
     * @param String $time Unix time
     * @return String
     */
    public function tiempoTranscurrido($time)
    {

        $time = time() - $time; // obtener el date exacto
        $time = ($time < 1) ? 1 : $time;
        $tokens = array(
            31536000 => 'año',
            2592000 => 'mes',
            604800 => 'semana',
            86400 => 'dia',
            3600 => 'hora',
            60 => 'minuto',
            1 => 'segundo'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? (($text == 'mes') ? 'es' : 's') : '');
        }
    }
    /**
     * Retorna TRUE si no encuentra el estado.
     * @return bolean
     */
    public function pendienteBajaTecnica($vehiculoId, $estado)
    {
        $bajaTecnica = MTHistoricoVehiculo::where('vehiculo_id', $vehiculoId)
            ->where('estado', $estado)
            ->get();
        return $bajaTecnica->isEmpty();
    }

    public function reporteCDT(Request $request)
    {
        try {
            $user = $request->user();
            $reporte = $this->medioTransporteRepository->showReportCDC($user->instalacion_id, $request);
            return $this->sendResponse($reporte, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    public function reporteParqueTotal()
    {
        try {
            $user = Auth::user();
            $reporte = $this->medioTransporteRepository->showReportParqueTotal($user->instalacion_id);
            return $this->sendResponse($reporte, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    public function fichaMediosTransporte(Request $request)
    {
        try {
            $reporte = $this->medioTransporteRepository->showFichaMediosTransporte($request);
            return $this->sendResponse($reporte, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    public function listaTipoFlotaInstalacion()
    {
        try {
            $user = Auth::user();
            $data = $this->medioTransporteRepository->getTipoFlotas($user->instalacion_id);
            return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    public function setCapacidadCargaUM($tipoFlota, $tipoVehiculo)
    {
        $lista = Str::contains($tipoVehiculo, [
            'Panel',
            'Panel (Carga)',
            'Camioneta',
            'Camión Volteo',
            'Camión Refrigerado',
            'Camión Plancha-Rampa',
            'Camión Furgon',
            'Cuña Tractora',
            'Ambulancia',
            'Remolque-Semiremolque',
            'Transportador de Autos',
            'Vehiculo Serv/Técnicos',
            'Veh/Auxilios',
            'ATV',
            'Cisterna Agua',
            'Cisterna Combustible',
        ]);
        if (($tipoFlota == 'Administrativo y de Servicio' || $tipoFlota == 'Turístico') && $lista) {
            return 'kg';
        } elseif ($tipoFlota == ' Especializado') {
            return 'kg';
        }
    }
    public function exportarReporteCDT(Request $request)
    {
        try {
            $user = $request->user();
            $reporte = $this->medioTransporteRepository->showReportCDC($user->instalacion_id, $request);
            $pdf = PDF::loadView('pdf.transporte.reporteCDT', ['data' => $reporte, 'tipo_flota' => $request['tipo_flota']]);
            return $pdf->download('reporte-CDT.pdf');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    public function exportarReporteFichaMT(Request $request)
    {
        try {
            $reporte = $this->medioTransporteRepository->showFichaMediosTransporte($request);
            $pdf = PDF::loadView('pdf.transporte.reporteFichaMT', ['data' => $reporte]);
            return $pdf->download('Ficha_medios_transporte.pdf');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

    //Coeficiente de Disposición Técnica
    public function coeficienteDisposicionTec_dashboard()
    {
        try {
            $dashboard = $this->medioTransporteRepository->coeficienteDisposicionTec();
            return $this->sendResponse($dashboard, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }
    //Coeficiente de Disposición Técnica TOTAL
    public function coeficienteDisposicionTecTOTAL_dashboard()
    {
        try {
            $dashboard = $this->medioTransporteRepository->coeficienteDisposicionTecTotal();
            return $this->sendResponse($dashboard, __('messages.app.data_retrieved', ['model' => $this->medioTransporteModel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->medioTransporteModel->model]), $exception->getMessage(), '500');
        }
    }

}
