<?php

namespace App\Repositories;

use App\Models\MTPlanMantenimiento;
use Illuminate\Support\Facades\DB;


/**
 * Class MTPlanMantenimientoRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTPlanMantenimientoRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [

  ];

  /**
   * Return searchable fields
   *
   * @return array
   */
  public function getFieldsSearchable()
  {
    return $this->fieldSearchable;
  }

  /**
   * Configure the Model
   **/
  public function model()
  {
    return MTPlanMantenimiento::class;
  }

  public function getReportPlanMtto($request)
  {
    $input = $request->all();
    $registros  = $this->model->with(
      'instalacions:id,entidad_id,nombre',
      'instalacions.entidades:id,nombre',)
      ->where('descripcion', '')
      ->get();
    dd($registros);

    if(!isset($registros) || $registros == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resgistros que mostrar']);
    return $registros;
  }

  public function getAnexos2Indicador2($request)
  {
    $registros  = DB::table('mtplanmantenimiento')
      ->join('mtgestionmantenimiento','mtgestionmantenimiento.id','=','mtplanmantenimiento.gestionmtto_id')
      ->where('mtplanmantenimiento.porCientoImpAcc','>','0')
      ->select('mtplanmantenimiento.id','mtgestionmantenimiento.anno','mtgestionmantenimiento.mes',
        'mtplanmantenimiento.descMtto','mtplanmantenimiento.unidadMedida','mtplanmantenimiento.accPrevTPlan','mtplanmantenimiento.accPrevTReal','mtplanmantenimiento.accPrevTPorCiento',
      'mtplanmantenimiento.accPrevCPlan','mtplanmantenimiento.accPrevCReal','mtplanmantenimiento.accPrevCPorCiento','mtplanmantenimiento.impTotal','mtplanmantenimiento.impContratado',
      'mtplanmantenimiento.totalAccMtto','mtplanmantenimiento.porCientoImpAcc','mtplanmantenimiento.hDD','mtplanmantenimiento.hFO','mtplanmantenimiento.porCientoHFOHDD')
      ->get();
    if(!isset($registros) || $registros == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resgistros que mostrar']);
    return $registros;
  }

//  public function listPlanMttoHFOHDD($request)
//  {
//
//    $less = $request['less'];
//    $greater = $request['greater'];
//    $month = $request['month'];
//    $year = $request['year'];
//    //$report = MTPlanMantenimiento::query();
//    $report = $this->model->with(
//      'entidads:id,nombre',
//      'entidads.mtinstalacions:entidad_id,municipio_id',
//      'entidads.mtinstalacions.municipios:id,provincia_id',
//      'entidads.mtinstalacions.municipios.provincia:id,nombre'
//    );
//
//
//    if(isset($less) && !empty($less))
//    {
//      $report = $report->where([['porCientoHFOHDD','<=',$less], ['porCientoHFOHDD','>',0]]);
//    };
//    if(isset($greater) && !empty($greater))
//    {
//      $report = $report->where([['porCientoHFOHDD', '>=', $greater], ['porCientoHFOHDD', '>', 0]]);
//    }
//    if(isset($month) && !empty($month)  && $month != 'Todos')
//    {
//      $report = $report->where('mes','=',$month );
//    }
//    if(isset($year) && !empty($year) && $year != 'Todos')
//    {
//      $report = $report->where('anno',$year,'=',$year);
//    }
//    $report = $report->groupBy('entidad','anno')->get();
//
////dd(response()->json($report));
//    //if (!isset($report) || $report == null)
//      //return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay datos que mostrar']);
//    return $report;
//  }


}
