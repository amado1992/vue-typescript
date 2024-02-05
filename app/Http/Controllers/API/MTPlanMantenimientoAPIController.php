<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\API\CreatePlanMantenimientoAPIRequest;
use App\Http\Requests\API\UpdatePlanMantenimientoAPIRequest;
use App\Http\Resources\MTReportPlanMttoHFOHDDResource;
use App\Models\MTPlanMantenimiento;
use App\Repositories\MTPlanMantenimientoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use Response;
use Illuminate\Support\Arr;
use function Sodium\add;

/**
 * Class MTPlanMantenimientoController
 * @package App\Http\Controllers\API
 */

class MTPlanMantenimientoAPIController extends AppBaseController
{
    /** @var  MTPlanMantenimientoRepository */
    private $planMantenimientoRepository;

    /** @var  MTPlanMantenimiento */
    private $planMantenimiento;

    public function __construct(MTPlanMantenimientoRepository $planMantenimientoRepo, MTPlanMantenimiento $planMantenimiento)
    {
        $this->planMantenimientoRepository = $planMantenimientoRepo;
        $this->planMantenimiento = $planMantenimiento;
    }

    /**
     * Display a listing of the MTPlanMantenimiento.
     * GET|HEAD /planMantenimientos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $planMantenimientos = $this->planMantenimientoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($planMantenimientos->toArray(), 'Plan Mantenimientos retrieved successfully');
    }

    /**
     * Store a newly created MTPlanMantenimiento in storage.
     * POST /planMantenimientos
     *
     * @param CreatePlanMantenimientoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePlanMantenimientoAPIRequest $request)
    {
        $input = $request->all();

        $planMantenimiento = $this->planMantenimientoRepository->create($input);

        return $this->sendResponse($planMantenimiento->toArray(), 'Plan Mantenimiento saved successfully');
    }

  // Devuelve los reportes del anexo2
  public function reportPlanMtto(Request $request)
  {
    try {
      $anexos = $this->planMantenimientoRepository->getReportPlanMtto($request);


      return $this->sendResponse($anexos, 'Anexos get successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planMantenimiento->model]), $exception->getMessage(), '500');
    }
  }

  public function getAnexos2Indicador2(Request $request)
  {
    try {
      $anexos = $this->planMantenimientoRepository->getAnexos2Indicador2($request);
      return $this->sendResponse($anexos, 'Anexos get successfully');
    } catch (Exception $exception) {
      Log::error($exception->getMessage());
      return $this->sendError(__('messages.app.data_not_retrieved', ['model' => $this->planMantenimiento->model]), $exception->getMessage(), '500');
    }
  }



//    // Devuelve los datos de la tabla mtplanmantenimiento
//    public function reportPlanMtto(Request $request)
//    {
//      $month = $request['month'];
//      $year = $request['year'];
//      $entity_id = $request['entity_id'];
//      $reporte  = MTPlanMantenimiento::query();
//      $reporte = $reporte
//        ->with('entidads:id,nombre',
//          'entidads.mtinstalacions:entidad_id,codigo'
//        );
//
//      if($year != 'Todos')
//      {
//        $reporte = $reporte->where('anno','=',$year);
//      };
//
//      if($month > 0)
//      {
//        $reporte = $reporte->where('mes','=',$month);
//      };
//
//      if(isset($entity_id) && !empty($entity_id))
//      {
//        $reporte = $reporte->where('entidad','=',$entity_id);
//      };
//
//      $reporte = $reporte->groupBy('anno','mes','entidad')->orderByDesc('anno')->get();
//      $result = $reporte->transform(function ($item)
//      {
//        return [
//          'year'=> $item->anno,
//          'month'=> $item->mes,
//          'entity'=> $item->entidads[0]['nombre'],
//          'installation'=> $item->entidads[0]['mtinstalacions'][0]['codigo'],
//        ];
//      });
//      return $this->sendResponse($result, 'ok');
//    }

//    // Reporte que muestra las provincias que representen el porciento de HFO/HDD por encima o igual al 5%
//    public function reportPlanMttoHFOHDD(Request $request)
//    {
//      $less = $request['less'];
//      $greater = $request['greater'];
//      $month = $request['month'];
//      $year = $request['year'];
//      $entity_id = $request['entity_id'];
//      $indicator = $request['indicator'];
//      $reporte  = MTPlanMantenimiento::query();
//      $reporte = $reporte
//        ->with('entidads:id,nombre',
//          'entidads.mtinstalacions:entidad_id,municipio_id',
//          'entidads.mtinstalacions.municipios:id,provincia_id',
//          'entidads.mtinstalacions.municipios.provincia:id,nombre'
//        );
//
//      if($year != 'Todos')
//      {
//        $reporte = $reporte->where('anno','=',$year);
//      };
//
//      if($month > 0)
//      {
//        $reporte = $reporte->where('mes','=',$month);
//      };
//
//      if(isset($entity_id) && !empty($entity_id))
//      {
//        $reporte = $reporte->where('entidad','=',$entity_id);
//      };
//
//      if(isset($indicator) && !empty($indicator))
//      {
//        if(isset($less) && !empty($less))
//        {
//          $reporte = $reporte->where([['porCientoHFOHDD','<=',$less], ['porCientoHFOHDD','>',0]]);
//        };
//
//        if(isset($greater) && !empty($greater))
//        {
//          $reporte = $reporte->where([['porCientoHFOHDD','>=',$greater], ['porCientoHFOHDD','>',0]]);
//        };
//      };
//
//      $reporte = $reporte->groupBy('entidad')->get();
//      $result = $reporte->transform(function ($item)
//      {
//        return [
//          'entity_name'=> $item->entidads[0]['nombre'],
//          'province_name'=>$item->entidads[0]['mtinstalacions'][0]['municipios']['provincia']['nombre'],
//          'percent_hfohdd'=>$item->porCientoHFOHDD
//        ];
//
//      });
//
//      return $this->sendResponse($result, 'ok');
//     //return $this->sendResponse($reporte, 'ok');
//    }
//
//    public function reporte1PlanMtto(Request $request)
//    {
//        $datosTabla = $request['datas'];
//        $mes = $request['mes'];
//        $anno = $request['anno'];
//        $porcientoMenor = $request['porcientoMenor'];
//        $porcientoMayor = $request['porcientoMayor'];
//        $indicador = $request['$indicador'];
//        $reporte  = MTPlanMantenimiento::query();
//        $entity_all_costs_total = [];
//
//        $reporte = $reporte->join('mtentidad', 'mtentidad.id', '=', 'mtplanmantenimiento.entidad')
//            ->select('mtplanmantenimiento.descMtto','mtplanmantenimiento.porCientoImpAcc','mtentidad.nombre')
//            ->where('mtplanmantenimiento.porCientoImpAcc', '!=', 0)
//            ->where('mtplanmantenimiento.porCientoImpAcc', '<', 95)
//            ->orWhere('mtplanmantenimiento.porCientoImpAcc', '>', 105)
//            ->groupBy('mtplanmantenimiento.descMtto','mtplanmantenimiento.entidad')
//            ->get();
//
//        return $this->sendResponse($reporte, 'ok');
//
//    }

    /**
     * Display the specified MTPlanMantenimiento.
     * GET|HEAD /planMantenimientos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MTPlanMantenimiento $planMantenimiento */
        $planMantenimiento = $this->planMantenimientoRepository->find($id);

        if (empty($planMantenimiento)) {
            return $this->sendError('Plan Mantenimiento not found');
        }

        return $this->sendResponse($planMantenimiento->toArray(), 'Plan Mantenimiento retrieved successfully');
    }

    /**
     * Update the specified MTPlanMantenimiento in storage.
     * PUT/PATCH /planMantenimientos/{id}
     *
     * @param int $id
     * @param UpdatePlanMantenimientoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlanMantenimientoAPIRequest $request)
    {
        $input = $request->all();

        /** @var MTPlanMantenimiento $planMantenimiento */
        $planMantenimiento = $this->planMantenimientoRepository->find($id);

        if (empty($planMantenimiento)) {
            return $this->sendError('Plan Mantenimiento not found');
        }

        $planMantenimiento = $this->planMantenimientoRepository->update($input, $id);

        return $this->sendResponse($planMantenimiento->toArray(), 'MTPlanMantenimiento updated successfully');
    }

    /**
     * Remove the specified MTPlanMantenimiento from storage.
     * DELETE /planMantenimientos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MTPlanMantenimiento $planMantenimiento */
        $planMantenimiento = $this->planMantenimientoRepository->find($id);

        if (empty($planMantenimiento)) {
            return $this->sendError('Plan Mantenimiento not found');
        }

        $planMantenimiento->delete();

        return $this->sendSuccess('Plan Mantenimiento deleted successfully');
    }
}
