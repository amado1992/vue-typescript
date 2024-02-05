<?php

namespace App\Http\Controllers;

use App\Models\MTGestionarAccidente;
use App\Repositories\MTGestionarAccidenteRepository;
use App\Http\Resources\MTGestionarAccidenteResource;
use App\Models\Traza;
use App\Utils\EventsUtil;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MTGestionarAccidenteController extends AppBaseController
{
  private $mtGestionarAccidenteRepository;
  private $mtGestionarAccidente;
  private $traza;

  public function __construct(MTGestionarAccidenteRepository $mtGestionarAccidenteRepository, MTGestionarAccidente $mtGestionarAccidente, Traza $traza)
  {
    $this->mtGestionarAccidenteRepository = $mtGestionarAccidenteRepository;
    $this->mtGestionarAccidente = $mtGestionarAccidente;
    $this->traza = $traza;
  }

  public function index()
  {
    $data = $this->mtGestionarAccidenteRepository->listMTGestionarAccidente();
    return $data;
  }

  public function store(Request $request)
  {
    try {
      $user = Auth::user();
      $data = $this->mtGestionarAccidenteRepository->createMTGestionarAccidente($request->all(), $user->instalacion_id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 1, EventsUtil::getUserId(), $this->mtGestionarAccidente->model, json_encode($data));
      return $this->sendResponse($data, __('messages.app.model_success', ['model' => $this->mtGestionarAccidente->model, 'operation' => 'creada']));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtGestionarAccidente->model, 'operation' => 'creada']), $exception->getMessage(), '500');
    }
  }

  public function show($id)
  {
    $data = MTGestionarAccidente::findOrFail($id);
    return new MTGestionarAccidenteResource($data);
  }

  public function update(Request $request, $id)
  {
    try {
      $data = $this->mtGestionarAccidenteRepository->updateMTGestionarAccidente($id, $request);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 2, EventsUtil::getUserId(), $this->mtGestionarAccidente->model, json_encode($data));
      return response()->json(['success' => true, 'data' => $data, 'msg' => 'Gestion de accidente actualizada OK']);
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function destroy(Request $request, $id)
  {
    try {
      $data = $this->mtGestionarAccidenteRepository->eliminarMTGestionarAccidente($id);
      Log::info(__('messages.app.model_success', ['model' => $this->mtGestionarAccidente->model, 'operation' => 'eliminada']) . ' con id = ' . $id);
      $this->traza->saveTraza($request->server->get('REMOTE_ADDR'), 3, EventsUtil::getUserId(), $this->mtGestionarAccidente->model, json_encode($data));
      return $data;
    } catch (\Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.model_error', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function vehiculoconmasaccidentes(Request $request)
  {
    try {
      $indicador = $this->mtGestionarAccidenteRepository->vehiculoconmasaccidentes($request);
      return $this->sendResponse($indicador, __('messages.app.data_retrieved', ['model' => $this->mtGestionarAccidente->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function reporteaccidentalidad(Request $request)
  {
    try {
      $data = $this->mtGestionarAccidenteRepository->reporte_accidentalidad($request);
      return $this->sendResponse($data, __('messages.app.data_retrieved', ['model' => $this->mtGestionarAccidente->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function horarioconmasaccidentes()
  {
    try {
      $horario = $this->mtGestionarAccidenteRepository->horarioconmasaccidentes();
      return $horario;
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function exportarreporteaccidentalidad(Request $request)
  {
    try {
      $reporte = $this->mtGestionarAccidenteRepository->reporte_accidentalidad_pdf($request);
      $pdf = PDF::loadView('pdf.transporte.reporteaccidentalidad', ['data' => $reporte, 'indicador' => $request['indicador']]);
      return $pdf->download('reporte-accidentalidad.pdf');
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }

  public function accidentalidad_dashboard()
  {
    try {
      $dashboard = $this->mtGestionarAccidenteRepository->dashboard();
      return $this->sendResponse($dashboard, __('messages.app.data_retrieved', ['model' => $this->mtGestionarAccidente->model]));
    } catch (\Exception $exception) {
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->mtGestionarAccidente->model]), $exception->getMessage(), '500');
    }
  }
}
