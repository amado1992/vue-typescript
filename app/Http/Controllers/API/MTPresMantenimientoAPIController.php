<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePresMantenimientoAPIRequest;
use App\Http\Requests\API\UpdatePresMantenimientoAPIRequest;
use App\Models\MTPlanMantenimiento;
use App\Models\MTPresMantenimiento;
use App\Repositories\PlanMantenimientoRepository;
use App\Repositories\PresMantenimientoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\PresMantenimientoResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class MTPresMantenimientoController
 * @package App\Http\Controllers\API
 */

class MTPresMantenimientoAPIController extends AppBaseController
{
    /** @var  PresMantenimientoRepository */
    private $presMantenimientoRepository;

    /** @var  MTPresMantenimiento */
    private $presMantenimiento;

    public function __construct(PresMantenimientoRepository $presMantenimientoRepo, MTPresMantenimiento $presMantenimiento)
    {
        $this->presMantenimientoRepository = $presMantenimientoRepo;
        $this->presMantenimiento = $presMantenimiento;
    }

    /**
     * Display a listing of the MTPresMantenimiento.
     * GET|HEAD /presMantenimientos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $presMantenimientos = $this->presMantenimientoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($presMantenimientos->toArray(), 'Pres Mantenimientos retrieved successfully');
    }

    /**
     * Store a newly created MTPresMantenimiento in storage.
     * POST /presMantenimientos
     *
     * @param CreatePresMantenimientoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePresMantenimientoAPIRequest $request)
    {
        $input = $request->all();

        $presMantenimiento = $this->presMantenimientoRepository->create($input);

        return $this->sendResponse($presMantenimiento->toArray(), 'Plan Mantenimiento saved successfully');
    }

    public function createAnexoTres(Request $request)
    {
        $datosTabla = $request['datas'];
        //$this->presMantenimiento->createAnexoDos($request);
        //dd($request);
        foreach ($datosTabla as $aux)
        {
            Arr::set($aux,'mes',$request['attributos']['mes']);
            Arr::set($aux,'anno',$request['attributos']['anno']);
            Arr::set($aux,'hDD',$request['attributos']['hdd']);
            Arr::set($aux,'hFO',$request['attributos']['hfo']);
            Arr::set($aux,'porCientoHFOHDD',$request['attributos']['porcientoH']);
            Arr::set($aux,'entidad',$request['entidad']);
            // dd($aux);
            //print_r($aux);
            $this->presMantenimientoRepository->create($aux);


        }
    }

    /**
     * Display the specified MTPresMantenimiento.
     * GET|HEAD /presMantenimientos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTPresMantenimiento $presMantenimiento */
        $presMantenimiento = $this->presMantenimientoRepository->find($id);

        if (empty($presMantenimiento)) {
            return $this->sendError('Pres Mantenimiento not found');
        }

        return $this->sendResponse($presMantenimiento->toArray(), 'Plan Mantenimiento retrieved successfully');
    }

    /**
     * Update the specified MTPresMantenimiento in storage.
     * PUT/PATCH /presMantenimientos/{id}
     *
     * @param int $id
     * @param UpdatePresMantenimientoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePresMantenimientoAPIRequest $request)
    {
        $input = $request->all();

        /** @var MTPresMantenimiento $presMantenimiento */
        $presMantenimiento = $this->presMantenimientoRepository->find($id);

        if (empty($presMantenimiento)) {
            return $this->sendError('Pres Mantenimiento not found');
        }

        $presMantenimiento = $this->presMantenimientoRepository->update($input, $id);

        return $this->sendResponse($presMantenimiento->toArray(), 'MTPlanMantenimiento updated successfully');
    }

    /**
     * Remove the specified MTPresMantenimiento from storage.
     * DELETE /presMantenimientos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MTPresMantenimiento $presMantenimiento */
        $presMantenimiento = $this->presMantenimientoRepository->find($id);

        if (empty($presMantenimiento)) {
            return $this->sendError('Pres Mantenimiento not found');
        }

        $presMantenimiento->delete();

        return $this->sendSuccess('Pres Mantenimiento deleted successfully');
    }

    public function reporte1PlanMtto(Request $request)
    {
        $datosTabla = $request['datas'];
        $mes = $request['mes'];
        $anno = $request['anno'];
        $porcientoMenor = $request['porcientoMenor'];
        $porcientoMayor = $request['porcientoMayor'];
        $indicador = $request['$indicador'];
        $reporte  = MTPresMantenimiento::query();
        $entity_all_costs_total = [];

        $reporte = $reporte->join('mtentidad', 'mtentidad.id', '=', 'mtpresmantenimiento.entidad')
            ->select('mtpresmantenimiento.descMtto','mtpresmantenimiento.porCientoMttoTot1','mtentidad.nombre')
            ->where('mtpresmantenimiento.porCientoMttoTot1', '!=', 0)
            ->where('mtpresmantenimiento.porCientoMttoTot1', '<', 95)
            ->orWhere('mtpresmantenimiento.porCientoMttoTot1', '>', 105)
            ->groupBy('mtpresmantenimiento.descMtto','mtpresmantenimiento.entidad')
            ->get();

        return $this->sendResponse($reporte, 'ok');

    }

    public function reporte2PlanMtto(Request $request)
    {
        $datosTabla = $request['datas'];
        $mes = $request['mes'];
        $anno = $request['anno'];
        $porcientoMenor = $request['porcientoMenor'];
        $porcientoMayor = $request['porcientoMayor'];
        $indicador = $request['$indicador'];
        //$reporte  = MTPresMantenimiento::query();
        $reporte  = DB::table('mtpresmantenimiento');
        $entity_all_costs_total = [];

        $reporte = $reporte
            ->join('mtentidad', 'mtentidad.id', '=', 'mtpresmantenimiento.entidad')
            ->join('mtosde', 'mtentidad.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre','mtosde.codigo',
                DB::raw('((mtpresmantenimiento.prespMttoTotal * 100) / (mtpresmantenimiento.realMttoTotal)) as porciento'),
            )
            ->where('mtpresmantenimiento.descMtto', '=', 'Total')
            ->groupBy('mtentidad.osde_id')
            ->get();

        return $this->sendResponse($reporte, 'ok');

    }

    public function reporte3PlanMtto(Request $request)
    {
        $datosTabla = $request['datas'];
        $mes = $request['mes'];
        $anno = $request['anno'];
        $porcientoMenor = $request['porcientoMenor'];
        $porcientoMayor = $request['porcientoMayor'];
        $indicador = $request['$indicador'];
        //$reporte  = MTPresMantenimiento::query();
        $reporte  = DB::table('mtpresmantenimiento');
        $entity_all_costs_total = [];

        $reporte = $reporte
            ->join('mtentidad', 'mtentidad.id', '=', 'mtpresmantenimiento.entidad')
            ->join('mtosde', 'mtentidad.osde_id', '=', 'mtosde.id')
            ->select('mtosde.nombre','mtosde.codigo',
                DB::raw('((mtpresmantenimiento.prespMttoTotal * 100) / (mtpresmantenimiento.realMttoTotal)) as porciento'),
            )
            ->where('mtpresmantenimiento.descMtto', '=', 'Total')
            ->groupBy('mtentidad.osde_id')
            ->get();

        return $this->sendResponse($reporte, 'ok');

    }

  public function getAnexos3Indicador1(Request $request)
  {
    try {
      $anexos = $this->presMantenimientoRepository->getAnexos3Indicador1($request);
      return $this->sendResponse($anexos, 'Anexos get successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planMantenimiento->model]), $exception->getMessage(), '500');
    }
  }

}
