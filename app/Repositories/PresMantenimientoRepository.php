<?php

namespace App\Repositories;

use App\Models\MTPresMantenimiento;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class PresMantenimientoRepository
 * @package App\Repositories
 * @version June 17, 2021, 7:12 pm CDT
*/

class PresMantenimientoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'descMtto',
        'unidadMedida',
        'prespMttoT1',
        'prespMttoT2',
        'prespMttoTotal',
        'prespMttoC1',
        'prespMttoC2',
        'prespMttoContrat',
        'realMttoT1',
        'realMttoT2',
        'realMttoTotal',
        'realMttoC1',
        'realMttoC2',
        'realMttoContrat',
        'porCientoMttoTot1',
        'porCientoMttoTot2',
        'porCientoMttoTot3',
        'porCientoMttoContrat1',
        'porCientoMttoContrat2',
        'porCientoMttoContrat3',
        'mes',
        'anno',
        'entidad'
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
        return MTPresMantenimiento::class;
    }

  public function getAnexos3Indicador1($request)
  {
    $registros  = DB::table('mtpresmantenimiento')
      ->join('mtgestionmantenimiento','mtgestionmantenimiento.id','=','mtpresmantenimiento.gestionmtto_id')
      ->where('mtpresmantenimiento.porCientoMttoTot1','>','0')
      ->where('mtpresmantenimiento.porCientoMttoTot2','>','0')
      ->where('mtpresmantenimiento.porCientoMttoTot3','>','0')
      ->where('mtpresmantenimiento.porCientoMttoContrat1','>','0')
      ->where('mtpresmantenimiento.porCientoMttoContrat2','>','0')
      ->where('mtpresmantenimiento.porCientoMttoContrat3','>','0')
      ->select('mtpresmantenimiento.id','mtgestionmantenimiento.anno','mtgestionmantenimiento.mes',
        'mtpresmantenimiento.descMtto','mtpresmantenimiento.unidadMedida','mtpresmantenimiento.prespMttoT1','mtpresmantenimiento.prespMttoT2',
        'mtpresmantenimiento.prespMttoTotal','mtpresmantenimiento.prespMttoC1','mtpresmantenimiento.prespMttoC2','mtpresmantenimiento.prespMttoContrat',
        'mtpresmantenimiento.realMttoT1','mtpresmantenimiento.realMttoT2','mtpresmantenimiento.realMttoTotal','mtpresmantenimiento.realMttoC1',
        'mtpresmantenimiento.realMttoC2','mtpresmantenimiento.realMttoContrat','mtpresmantenimiento.porCientoMttoTot1','mtpresmantenimiento.porCientoMttoTot2',
        'mtpresmantenimiento.porCientoMttoTot3','mtpresmantenimiento.porCientoMttoContrat1','mtpresmantenimiento.porCientoMttoContrat2','mtpresmantenimiento.porCientoMttoContrat3')
      ->get();
    if(!isset($registros) || $registros == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay resgistros que mostrar']);
    return $registros;
  }
}
