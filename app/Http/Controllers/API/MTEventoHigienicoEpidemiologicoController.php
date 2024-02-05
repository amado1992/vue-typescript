<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateMTEventoHERequest;
use App\Http\Requests\API\UpdateMTEventoHERequest;
use Illuminate\Http\Request;
use App\Utils\EventsUtil;
use App\Models\Traza;
use App\Models\MTEventoHigienicoEpidemiologico;
use App\Models\MTPlanAccionFicheros;
use App\Repositories\MTEventoHigienicoEpidemiologicoRepository;
use App\Repositories\MTPlanAccionRepository;
use App\Repositories\MTDocumentoResumenEHERepository;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;

class MTEventoHigienicoEpidemiologicoController extends AppBaseController
{
    private $traza;
    private $eventoHEmodel;
    private $eventoHErepository;
    private $planAccionRepository;
    private $docresumenRepository;

    public function __construct(Traza $traza, MTEventoHigienicoEpidemiologico $eventoHEmodel, MTEventoHigienicoEpidemiologicoRepository $eventoHErepository, MTPlanAccionRepository $planAccionRepository, MTDocumentoResumenEHERepository $docresumenRepository)
    {
        $this->traza = $traza;
        $this->eventoHEmodel = $eventoHEmodel;
        $this->eventoHErepository = $eventoHErepository;
        $this->planAccionRepository = $planAccionRepository;
        $this->docresumenRepository = $docresumenRepository;
    }

    public function index(Request $request)
    {
        try {
            $eventoEH = $this->eventoHErepository->paginateListEventoEH($request);
            return $this->sendResponse($eventoEH, __('messages.app.data_retrieved', ['model' => $this->eventoHEmodel->model]));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->eventoHEmodel->model]), $exception->getMessage(), '500');
        }
    }

    public function store(CreateMTEventoHERequest $request)
    {
        try {
            $user = $request->user();
            $input = $request->all();
            $input = Arr::add($input, 'instalacion_id', $user->instalacion_id);
            $input = Arr::add($input, 'fecha_registro', now());
            $input = Arr::set($input, 'clasificacion_evento', implode(',', $input['clasificacion_evento']));
            $input = Arr::set($input, 'estado_proceso', 'Evento en Investigación');
            $input = Arr::set($input, 'cod_registro', $this->eventoHErepository->genareteCodRegistro());
            $eventoEH = $this->eventoHErepository->create($input);
            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->eventoHEmodel->model, json_encode($eventoEH));
            return $this->sendResponse($eventoEH->toArray(), __('messages.app.model_success', ['model' => $this->eventoHEmodel->model, 'operation' => 'creada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->eventoHEmodel->model, 'operation' => 'creada']), $exception->getMessage(), '500');
        }
    }

    public function show($id)
    {
        $eventoEH = $this->eventoHErepository->find($id);

        if (empty($eventoEH)) {
            return $this->sendError('EPH no encontrado');
        }

        return $this->sendResponse($eventoEH->toArray(), 'EPH retrieved successfully');
    }

    public function update($id, UpdateMTEventoHERequest $request)
    {
        try {
            $input = $request->all();
            $input = Arr::set($input, 'clasificacion_evento', implode(',', $input['clasificacion_evento']));
            $objActualizar = $eventoEH = $this->eventoHErepository->find($id);

            if (empty($eventoEH)) {
                return $this->sendError('Dato no encontrado');
            }
            $input = $this->cambiarEstadoProceso($input);
            $eventoEH = $this->eventoHErepository->update($input, $id);
            $objArray = ['actualizar' => $objActualizar, 'actualizado' => $eventoEH];

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->eventoHEmodel->model, json_encode($objArray));
            return $this->sendResponse($eventoEH->toArray(), __('messages.app.model_success', ['model' => $this->eventoHEmodel->model, 'operation' => 'actualizada']));
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->eventoHEmodel->model, 'operation' => 'actualizada']), $exception->getMessage(), '500');
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $eventoEH = $this->eventoHErepository->find($id);

            if (empty($eventoEH)) {
                return $this->sendError('Dato no encontrado');
            }

            $eventoEH->delete();

            $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->eventoHEmodel->model, json_encode($eventoEH));
            return $this->sendSuccess('Dato eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => $this->eventoHEmodel->model, 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function cambiarEstadoProceso($proceso)
    {
        $seguimiento_resultado = ($proceso['tipo_seguimiento'] === 'Análisis de muestra' && $proceso['resultado_seguimiento_evento'] === 'Negativa') ? true : false;
        $existenciafoco_resultado = ($proceso['tipo_seguimiento'] === 'Verificación de la Existencia Foco' && $proceso['resultado_seguimiento_evento'] === 'Eliminado') ? true : false;
        $existenciabrote_resultado = ($proceso['tipo_seguimiento'] === 'Verificación de la Existencia Brote' && $proceso['resultado_seguimiento_evento'] === 'Eliminado') ? true : false;
        $medidadisciplinaria = (!empty($proceso['medida_disciplinaria']) && $proceso['plan_accion'] === true) ? true : false;

        if ($proceso['plan_accion'] === true || $medidadisciplinaria) {
            return $proceso = Arr::set($proceso, 'estado_proceso', 'Evento en Proceso de Respuesta');
        } elseif ($proceso['seguimiento_evento'] === true) {
            return $proceso = Arr::set($proceso, 'estado_proceso', 'Evento en Seguimiento');
        } elseif ($seguimiento_resultado || $existenciafoco_resultado || $existenciabrote_resultado) {
            $proceso = Arr::set($proceso, 'fecha_cierre', now());
            $proceso = Arr::set($proceso, 'estado_proceso', 'Evento Cerrado');
            $proceso = Arr::set($proceso, 'informe_conclusivo', true);
            return $proceso;
        } else {
            return $proceso;
        }
    }

    public function exportarResumenPDF(Request $request)
    {
        try {
            $resumen = $request->all();
            $pdf = PDF::loadView('pdf.eventoHE.resumenInformeConclusivoEHE', ['data' => $resumen]);
            return $pdf->download('Resumen.pdf');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->eventoHEmodel->model]), $exception->getMessage(), '500');
        }
    }

    public function uploadPlanAccion(Request $request)
    {
        try {
            $file = $request->file('file');
            $input = $request->only(['codigo_registro']);
            $payload = $request->only(['ehe_id']);

            $payload['url'] = Storage::putFile('EHE/PLANACCION' . $input['codigo_registro'], $file);
            $payload['nombre'] = $file->getClientOriginalName();
            $this->planAccionRepository->create($payload);
            return $this->sendSuccess('Fichero almacenado correctamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->eventoHEmodel->model]), $exception->getMessage(), '500');
        }
    }

    public function listarPlanAccion(Request $request)
    {
        $plan = $this->planAccionRepository->listplanAccion_($request);

        if (empty($plan)) {
            return $this->sendError('No se encontró un plan de acción', 'Ficheros no encontrado');
        }

        return $this->sendResponse($plan->toArray(), 'File retrieved successfully');
    }

    public function deletePlanAccion($id)
    {
        try {
            $plan = $this->planAccionRepository->find($id);

            if (empty($plan)) {
                return $this->sendError('Dato no encontrado');
            }
            Storage::delete($plan->url);
            $plan->delete();
            $allplan = $this->planAccionRepository->all();
            return $this->sendResponse($allplan->toArray(), 'Fichero eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => 'PlanAccionFichero', 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function downloadPlanAccion(Request $request)
    {
        $input = $request->all();
        $file = Storage::download($input['url'], $input['nombre']);

        return $file;
    }
    #Documentos-Resumen
    public function uploadDocResumen(Request $request)
    {
        try {
            $file = $request->file('file');
            $input = $request->only(['codigo_registro']);
            $payload = $request->only(['ehe_id']);

            $payload['url'] = Storage::putFile('EHE/RESUMEN' . $input['codigo_registro'], $file);
            $payload['nombre'] = $file->getClientOriginalName();
            $this->docresumenRepository->create($payload);
            return $this->sendSuccess('Fichero almacenado correctamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.data_not_retrieved', ['model' => 'MTDocumentoResumenEHE']), $exception->getMessage(), '500');
        }
    }

    public function downloadDocResumen(Request $request)
    {
        $input = $request->all();
        $file = Storage::download($input['url'], $input['nombre']);

        return $file;
    }

    public function deleteDocResumen($id)
    {
        try {
            $doc = $this->docresumenRepository->find($id);

            if (empty($doc)) {
                return $this->sendError('Dato no encontrado');
            }
            Storage::delete($doc->url);
            $doc->delete();
            $alldoc = $this->docresumenRepository->all();
            return $this->sendResponse($alldoc->toArray(), 'Fichero eliminado satisfactoriamente');
        } catch (\Exception $exception) {
            return $this->sendError(__('messages.app.model_error', ['model' => 'MTDocumentoResumenEHE', 'operation' => 'eliminada']), $exception->getMessage(), '500');
        }
    }

    public function listarDocResumen(Request $request)
    {
        $resumen = $this->docresumenRepository->listDocumentosResumen_($request);

        if (empty($resumen)) {
            return $this->sendError('No se encontraron documentos de resumen', 'Ficheros no encontrado');
        }

        return $this->sendResponse($resumen->toArray(), 'File retrieved successfully');
    }
}
