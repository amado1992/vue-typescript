<?php

namespace App\Repositories;

use App\Models\MTEntidad;
use App\Models\MtIndicadoresLineamiento;
use App\Repositories\BaseRepository;
use App\Utils\EventsUtil;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use \stdClass;


/**
 * Class MTIndicadoresLineaminetoRepository
 * @package App\Repositories
 * @version June 23, 2021, 10:08 pm CDT
 */
class MTIndicadoresLineamientoRepository extends BaseRepository
{
  /**
   * @var array
   */
  protected $fieldSearchable = [
    'mes',
    'anno',
    'instalacion_id',
    'licsanitaria_id',
    'avalcitma_id',
    'avalapci_id',
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
    return MtIndicadoresLineamiento::class;
  }

  public function listMTIndicadorLineamiento($request)
  {
    $indicadores = $this->model->with([
      'instalaciones:id,nombre',
      'licencia:id,estado',
      'avalcitma:id,estado',
      'avalapci:id,estado'
    ])
    ->where('instalacion_id',$request['instalacion_id'])
    ->orderBy('created_at', 'desc')
    ->get();

    if (!isset($indicadores) || $indicadores == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay indicadores que mostrar']);
    return response()->json($indicadores);
  }

  public function getAllPaginateIndicadores($request)
  {
    $filter = $request['search'];
    $callBackInstalation = EventsUtil::callBack('instalacion_id', 'like', '%' . $filter . '%');
    $callBackLic = EventsUtil::callBack('licsanitaria_id', 'like', '%' . $filter . '%');
    $callBackAvalCitma = EventsUtil::callBack('avalcitma_id', 'like', '%' . $filter . '%');
    $callBackAvalAPCI = EventsUtil::callBack('avalapci_id', 'like', '%' . $filter . '%');
    return $this->model->with([
      'instalaciones:id,nombre',
      'licencia:id,estado',
      'avalcitma:id,estado',
      'avalapci:id,estado'
    ])
      ->where('mes', 'like', '%' . $filter . '%')
      ->where('anno', 'like', '%' . $filter . '%')
      ->orWhereHas('instalaciones', $callBackInstalation)
      ->orWhereHas('licencia', $callBackAvalCitma)
      ->orWhereHas('avalcitma', $callBackLic)
      ->orWhereHas('avalapci', $callBackAvalAPCI)
      ->orderByDesc('created_at')
      ->paginate($request['itemsPerPage']);
  }

  // Reporte - Lic. y Avales
  public function getLicenciasAvales($request)
  {
    $indicadores = DB::table('mtindicadores_lineamiento')
      ->join('mtlic_sanitaria_nomenclador', 'mtindicadores_lineamiento.licsanitaria_id', '=', 'mtlic_sanitaria_nomenclador.id')
      ->join('mtaval_citma_nomenclador', 'mtindicadores_lineamiento.avalcitma_id', '=', 'mtaval_citma_nomenclador.id')
      ->join('mtaval_apci_nomenclador', 'mtindicadores_lineamiento.avalapci_id', '=', 'mtaval_apci_nomenclador.id')
      ->join('mtinstalacion', 'mtindicadores_lineamiento.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtentidad', function ($join) {
        $join->on('mtinstalacion.entidad_id', '=', 'mtentidad.id');
      })
      ->join('mttipoinst', function ($join) {
        $join->on('mtinstalacion.tipoInst_id', '=', 'mttipoinst.id');
      })
      ->select(
        'mtindicadores_lineamiento.*',
        'mtlic_sanitaria_nomenclador.estado as licsanitaria',
        'mtaval_citma_nomenclador.estado as avalcitma',
        'mtaval_apci_nomenclador.estado as avalapci',
        'mtinstalacion.nombre as instalacion',
        'mttipoinst.nombre as tipo_instalacion',
        'mtinstalacion.entidad_id as entidad_id',
      )
      ->select('mtentidad.nombre as entidad', 'mttipoinst.nombre as tipo_instalacion')
      //<editor-fold defaultstate="collapsed" desc="Lic. sanitaria">
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Retirada' then 1 end) as lic_retirada")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Vigente' then 1 end) as lic_vigente")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'En trámite' then 1 end) as lic_tramite")
      //</editor-fold>
      //<editor-fold defaultstate="collapsed" desc="Aval Citma">
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vencida' then 1 end) as avalcitma_vencida")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'En trámite ' then 1 end) as avalcitma_tramite")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vigente' then 1 end) as avalcitma_vigente")
      //</editor-fold>
      //<editor-fold defaultstate="collapsed" desc="Aval Apci">
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vencida' then 1 end) as avalapci_vencida")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'En trámite ' then 1 end) as avalapci_tramite")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vigente' then 1 end) as avalapci_vigente")
      //</editor-fold>
      //total de instalaciones por OSDE/entidad
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id then 1 end) as total_inst_xentidad")
      //total de instalaciones q tienen algun estado de lic
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xlic")
      //total de instalaciones q tienen algun estado de avalcitma
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_citma_nomenclador.estado = 'Vencida' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xavalcitma")
      //total de instalaciones q tienen algun estado de avalapci
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_apci_nomenclador.estado = 'Vencida' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xavalapci")
      //calculo % Lic. Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtlic_sanitaria_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoLicVigente")
      //calculo % Aval Citma Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_citma_nomenclador.estado = 'Vencida' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoAvalCitmaVigente")
      //calculo % Aval Apci Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_apci_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_apci_nomenclador.estado = 'Vencida' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoAvalApciVigente")

      //calculo % Lic. Saniaria Vigente - Total Alojamiento
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtlic_sanitaria_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Alojamiento') then 1 end) / count(case when (mtinstalacion.entidad_id = mtentidad.id and mttipoinst.nombre = 'Instalacion Alojamiento') and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) * 100,0) as totalAlojamiento_calculoXcientoLicVigente")
      //calculo % Aval Citma Vigente - Total Alojamiento
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Alojamiento') then 1 end) / count(case when (mtinstalacion.entidad_id = mtentidad.id and mttipoinst.nombre = 'Instalacion Alojamiento') and (mtaval_citma_nomenclador.estado = 'Retirada' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) * 100,0) as totalAlojamiento_calculoXcientoAvalCitmaVigente")
      //calculo % Aval Apci - Total Alojamiento
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_apci_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Alojamiento') then 1 end) / count(case when (mtinstalacion.entidad_id = mtentidad.id and mttipoinst.nombre = 'Instalacion Alojamiento') and (mtaval_apci_nomenclador.estado = 'Retirada' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) * 100,0) as totalAlojamiento_calculoXcientoAvalApciVigente")

      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Retirada' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_lic_retirada")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_lic_vigente")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'En trámite' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_lic_tramite")

      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vencida' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_avalcitma_vencida")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'En trámite ' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_avalcitma_tramite")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_avalcitma_vigente")

      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vencida' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_avalapci_vencida")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'En trámite ' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_avalapci_tramite")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Alojamiento' then 1 end) as totalAlojamiento_avalapci_vigente")

      //calculo % Lic. Saniaria Vigente - Total Instalacion Extra Hotelera
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtlic_sanitaria_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Extra Hotelera') then 1 end) / count(case when (mtinstalacion.entidad_id = mtentidad.id and mttipoinst.nombre = 'Instalacion Extra Hotelera') and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) * 100,0) as totalExtrahotelera_calculoXcientoLicVigente")
      //calculo % Aval Citma Vigente - Total Instalacion Extra Hotelera
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Extra Hotelera') then 1 end) / count(case when (mtinstalacion.entidad_id = mtentidad.id and mttipoinst.nombre = 'Instalacion Extra Hotelera') and (mtaval_citma_nomenclador.estado = 'Retirada' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) * 100,0) as totalExtrahotelera_calculoXcientoAvalCitmaVigente")
      //calculo % Aval Apci - Total Instalacion Extra Hotelera
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_apci_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Extra Hotelera') then 1 end) / count(case when (mtinstalacion.entidad_id = mtentidad.id and mttipoinst.nombre = 'Instalacion Extra Hotelera') and (mtaval_apci_nomenclador.estado = 'Retirada' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) * 100,0) as totalExtrahotelera_calculoXcientoAvalApciVigente")

      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Retirada' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_lic_retirada")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_lic_vigente")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'En trámite' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_lic_tramite")

      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vencida' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_avalcitma_vencida")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'En trámite ' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_avalcitma_tramite")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_avalcitma_vigente")

      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vencida' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_avalapci_vencida")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'En trámite ' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_avalapci_tramite")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vigente' and mttipoinst.nombre = 'Instalacion Extra Hotelera' then 1 end) as totalExtrahotelera_avalapci_vigente")

      ->where('mes', $request['mes'])
      ->where('anno', $request['anno'])
      ->where('mtentidad.nombre', '<>', 'Servitur')
      ->where('mtentidad.nombre', '<>', 'Cubasol')
      ->where('mttipoinst.nombre', 'Instalacion Extra Hotelera') // TODO Activar en produccion
      ->where('mttipoinst.nombre', 'Instalacion Alojamiento') // TODO Activar en produccion
      ->groupBy('entidad', 'tipo_instalacion')
      ->get();

    // $ultimaFila = $this->totalAlojamientoRow($indicadores, 'Total Alojamiento');
    // $ultimaFila = $this->totalExtraHoteleraRow($indicadores, 'Total extrahotelera');
    $ultimaFila = $this->ultimaFila($indicadores, 'Total Sector Turismo');
    if (!isset($indicadores) || $indicadores == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay indicadores que mostrar']);
    return $ultimaFila;
  }

  public function totalAlojamientoRow($arreglo)
  {
    $indicadores = new stdClass();
    $indicadores->entidad = 'Total alojamiento';
    $indicadores->total_inst_xentidad = 0;
    $indicadores->total_inst_xlic = 0;
    $indicadores->lic_retirada = 0;
    $indicadores->lic_vigente = 0;
    $indicadores->lic_tramite = 0;
    $indicadores->calculoXcientoLicVigente = 0;
    $indicadores->total_inst_xavalcitma = 0;
    $indicadores->avalcitma_vencida = 0;
    $indicadores->avalcitma_tramite = 0;
    $indicadores->avalcitma_vigente = 0;
    $indicadores->calculoXcientoAvalCitmaVigente = 0;
    $indicadores->total_inst_xavalapci = 0;
    $indicadores->avalapci_vencida = 0;
    $indicadores->avalapci_tramite = 0;
    $indicadores->avalapci_vigente = 0;
    $indicadores->calculoXcientoAvalApciVigente = 0;
    foreach ($arreglo as $a) {
      $indicadores->total_inst_xentidad += $a->total_inst_xentidad;
      $indicadores->total_inst_xlic += $a->total_inst_xlic;
      $indicadores->lic_retirada += $a->totalAlojamiento_lic_retirada;
      $indicadores->lic_vigente += $a->totalAlojamiento_lic_vigente;
      $indicadores->lic_tramite += $a->totalAlojamiento_lic_tramite;
      $indicadores->calculoXcientoLicVigente += $a->totalAlojamiento_calculoXcientoLicVigente;
      $indicadores->total_inst_xavalcitma += $a->total_inst_xavalcitma;
      $indicadores->avalcitma_vencida += $a->totalAlojamiento_avalcitma_vencida;
      $indicadores->avalcitma_tramite += $a->totalAlojamiento_avalcitma_tramite;
      $indicadores->avalcitma_vigente += $a->totalAlojamiento_avalcitma_vigente;
      $indicadores->calculoXcientoAvalCitmaVigente += $a->totalAlojamiento_calculoXcientoAvalCitmaVigente;
      $indicadores->total_inst_xavalapci += $a->total_inst_xavalapci;
      $indicadores->avalapci_vencida += $a->totalAlojamiento_avalapci_vencida;
      $indicadores->avalapci_tramite += $a->totalAlojamiento_avalapci_tramite;
      $indicadores->avalapci_vigente += $a->totalAlojamiento_avalapci_vigente;
      $indicadores->calculoXcientoAvalApciVigente += $a->totalAlojamiento_calculoXcientoAvalApciVigente;
    }
    $arreglo[count($arreglo)] = $indicadores;
    return $arreglo;
  }

  public function totalExtraHoteleraRow($arreglo)
  {
    $indicadores = new stdClass();
    $indicadores->entidad = 'Total extrahotelera';
    $indicadores->total_inst_xentidad = 0;
    $indicadores->total_inst_xlic = 0;
    $indicadores->lic_retirada = 0;
    $indicadores->lic_vigente = 0;
    $indicadores->lic_tramite = 0;
    $indicadores->calculoXcientoLicVigente = 0;
    $indicadores->total_inst_xavalcitma = 0;
    $indicadores->avalcitma_vencida = 0;
    $indicadores->avalcitma_tramite = 0;
    $indicadores->avalcitma_vigente = 0;
    $indicadores->calculoXcientoAvalCitmaVigente = 0;
    $indicadores->total_inst_xavalapci = 0;
    $indicadores->avalapci_vencida = 0;
    $indicadores->avalapci_tramite = 0;
    $indicadores->avalapci_vigente = 0;
    $indicadores->calculoXcientoAvalApciVigente = 0;
    foreach ($arreglo as $a) {
      $indicadores->total_inst_xentidad += $a->total_inst_xentidad;
      $indicadores->total_inst_xlic += $a->total_inst_xlic;
      $indicadores->lic_retirada += $a->totalExtrahotelera_lic_retirada;
      $indicadores->lic_vigente += $a->totalExtrahotelera_lic_vigente;
      $indicadores->lic_tramite += $a->totalExtrahotelera_lic_tramite;
      $indicadores->calculoXcientoLicVigente += $a->totalExtrahotelera_calculoXcientoLicVigente;
      $indicadores->total_inst_xavalcitma += $a->total_inst_xavalcitma;
      $indicadores->avalcitma_vencida += $a->totalExtrahotelera_avalcitma_vencida;
      $indicadores->avalcitma_tramite += $a->totalExtrahotelera_avalcitma_tramite;
      $indicadores->avalcitma_vigente += $a->totalExtrahotelera_avalcitma_vigente;
      $indicadores->calculoXcientoAvalCitmaVigente += $a->totalExtrahotelera_calculoXcientoAvalCitmaVigente;
      $indicadores->total_inst_xavalapci += $a->total_inst_xavalapci;
      $indicadores->avalapci_vencida += $a->totalExtrahotelera_avalapci_vencida;
      $indicadores->avalapci_tramite += $a->totalExtrahotelera_avalapci_tramite;
      $indicadores->avalapci_vigente += $a->totalExtrahotelera_avalapci_vigente;
      $indicadores->calculoXcientoAvalApciVigente += $a->totalExtrahotelera_calculoXcientoAvalApciVigente;
    }
    $arreglo[count($arreglo)] = $indicadores;
    return $arreglo;
  }

  public function ultimaFila($arreglo, $totalName)
  {
    $indicadores = new stdClass();
    $indicadores->total_inst_xentidad = 0;
    $indicadores->total_inst_xlic = 0;
    $indicadores->lic_retirada = 0;
    $indicadores->lic_vigente = 0;
    $indicadores->lic_tramite = 0;
    $indicadores->calculoXcientoLicVigente = 0;
    $indicadores->total_inst_xavalcitma = 0;
    $indicadores->avalcitma_vencida = 0;
    $indicadores->avalcitma_tramite = 0;
    $indicadores->avalcitma_vigente = 0;
    $indicadores->calculoXcientoAvalCitmaVigente = 0;
    $indicadores->total_inst_xavalapci = 0;
    $indicadores->avalapci_vencida = 0;
    $indicadores->avalapci_tramite = 0;
    $indicadores->avalapci_vigente = 0;
    $indicadores->calculoXcientoAvalApciVigente = 0;
    foreach ($arreglo as $a) {
      $indicadores->entidad = $totalName;
      $indicadores->total_inst_xentidad += $a->total_inst_xentidad;
      $indicadores->total_inst_xlic += $a->total_inst_xlic;
      $indicadores->lic_retirada += $a->lic_retirada;
      $indicadores->lic_vigente += $a->lic_vigente;
      $indicadores->lic_tramite += $a->lic_tramite;
      $indicadores->calculoXcientoLicVigente += $a->calculoXcientoLicVigente;
      $indicadores->total_inst_xavalcitma += $a->total_inst_xavalcitma;
      $indicadores->avalcitma_vencida += $a->avalcitma_vencida;
      $indicadores->avalcitma_tramite += $a->avalcitma_tramite;
      $indicadores->avalcitma_vigente += $a->avalcitma_vigente;
      $indicadores->calculoXcientoAvalCitmaVigente += $a->calculoXcientoAvalCitmaVigente;
      $indicadores->total_inst_xavalapci += $a->total_inst_xavalapci;
      $indicadores->avalapci_vencida += $a->avalapci_vencida;
      $indicadores->avalapci_tramite += $a->avalapci_tramite;
      $indicadores->avalapci_vigente += $a->avalapci_vigente;
      $indicadores->calculoXcientoAvalApciVigente += $a->calculoXcientoAvalApciVigente;
    }
    $arreglo[count($arreglo)] = $indicadores;
    return $arreglo;
  }

  // Reporte - List servitur
  public function getListaServitur($request)
  {
    $indicadores = DB::table('mtindicadores_lineamiento')
      ->join('mtlic_sanitaria_nomenclador', 'mtindicadores_lineamiento.licsanitaria_id', '=', 'mtlic_sanitaria_nomenclador.id')
      ->join('mtaval_citma_nomenclador', 'mtindicadores_lineamiento.avalcitma_id', '=', 'mtaval_citma_nomenclador.id')
      ->join('mtaval_apci_nomenclador', 'mtindicadores_lineamiento.avalapci_id', '=', 'mtaval_apci_nomenclador.id')
      ->join('mtinstalacion', 'mtindicadores_lineamiento.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtentidad', function ($join) {
        $join->on('mtinstalacion.entidad_id', '=', 'mtentidad.id');
      })
      ->join('mttipoinst', function ($join) {
        $join->on('mtinstalacion.tipoInst_id', '=', 'mttipoinst.id');
      })
      ->select(
        'mtindicadores_lineamiento.*',
        'mtlic_sanitaria_nomenclador.estado as licsanitaria',
        'mtaval_citma_nomenclador.estado as avalcitma',
        'mtaval_apci_nomenclador.estado as avalapci',
        'mtinstalacion.nombre as instalacion',
        'mttipoinst.nombre as tipo_instalacion',
        'mtinstalacion.entidad_id as entidad_id',


      )
      ->select('mtentidad.nombre as entidad', 'mttipoinst.nombre as tipo_instalacion')
      //<editor-fold defaultstate="collapsed" desc="Lic. sanitaria">
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Retirada' then 1 end) as lic_retirada")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Vigente' then 1 end) as lic_vigente")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'En trámite' then 1 end) as lic_tramite")
      //</editor-fold>
      //<editor-fold defaultstate="collapsed" desc="Aval Citma">
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vencida' then 1 end) as avalcitma_vencida")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'En trámite ' then 1 end) as avalcitma_tramite")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vigente' then 1 end) as avalcitma_vigente")
      //</editor-fold>
      //<editor-fold defaultstate="collapsed" desc="Aval Apci">
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vencida' then 1 end) as avalapci_vencida")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'En trámite ' then 1 end) as avalapci_tramite")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vigente' then 1 end) as avalapci_vigente")
      //</editor-fold>
      //total de instalaciones por OSDE/entidad
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id then 1 end) as total_inst_xentidad")
      //total de instalaciones q tienen algun estado de lic
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xlic")
      //total de instalaciones q tienen algun estado de avalcitma
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_citma_nomenclador.estado = 'Vencida' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xavalcitma")
      //total de instalaciones q tienen algun estado de avalapci
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_apci_nomenclador.estado = 'Vencida' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xavalapci")
      //calculo % Lic. Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtlic_sanitaria_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoLicVigente")
      //calculo % Aval Citma Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_citma_nomenclador.estado = 'Vencida' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoAvalCitmaVigente")
      //calculo % Aval Apci Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_apci_nomenclador.estado = 'Vencida' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoAvalApciVigente")

      ->where('mes', $request['mes'])
      ->where('anno', $request['anno'])
      ->where('mtentidad.nombre', 'Servitur')
      ->groupBy('entidad')
      ->get();

    $ultimaFila = $this->ultimaFila($indicadores, 'Servitur');
    if (!isset($indicadores) || $indicadores == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay indicadores que mostrar']);
    return $ultimaFila;
  }

  // Reporte - List cubasol
  public function getListaCubasol($request)
  {
    $indicadores = DB::table('mtindicadores_lineamiento')
      ->join('mtlic_sanitaria_nomenclador', 'mtindicadores_lineamiento.licsanitaria_id', '=', 'mtlic_sanitaria_nomenclador.id')
      ->join('mtaval_citma_nomenclador', 'mtindicadores_lineamiento.avalcitma_id', '=', 'mtaval_citma_nomenclador.id')
      ->join('mtaval_apci_nomenclador', 'mtindicadores_lineamiento.avalapci_id', '=', 'mtaval_apci_nomenclador.id')
      ->join('mtinstalacion', 'mtindicadores_lineamiento.instalacion_id', '=', 'mtinstalacion.id')
      ->join('mtentidad', function ($join) {
        $join->on('mtinstalacion.entidad_id', '=', 'mtentidad.id');
      })
      ->join('mttipoinst', function ($join) {
        $join->on('mtinstalacion.tipoInst_id', '=', 'mttipoinst.id');
      })
      ->select(
        'mtindicadores_lineamiento.*',
        'mtlic_sanitaria_nomenclador.estado as licsanitaria',
        'mtaval_citma_nomenclador.estado as avalcitma',
        'mtaval_apci_nomenclador.estado as avalapci',
        'mtinstalacion.nombre as instalacion',
        'mttipoinst.nombre as tipo_instalacion',
        'mtinstalacion.entidad_id as entidad_id',


      )
      ->select('mtentidad.nombre as entidad', 'mttipoinst.nombre as tipo_instalacion')
      //<editor-fold defaultstate="collapsed" desc="Lic. sanitaria">
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Retirada' then 1 end) as lic_retirada")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Vigente' then 1 end) as lic_vigente")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'En trámite' then 1 end) as lic_tramite")
      //</editor-fold>
      //<editor-fold defaultstate="collapsed" desc="Aval Citma">
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vencida' then 1 end) as avalcitma_vencida")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'En trámite ' then 1 end) as avalcitma_tramite")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vigente' then 1 end) as avalcitma_vigente")
      //</editor-fold>
      //<editor-fold defaultstate="collapsed" desc="Aval Apci">
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vencida' then 1 end) as avalapci_vencida")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'En trámite ' then 1 end) as avalapci_tramite")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vigente' then 1 end) as avalapci_vigente")
      //</editor-fold>
      //total de instalaciones por OSDE/entidad
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id then 1 end) as total_inst_xentidad")
      //total de instalaciones q tienen algun estado de lic
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xlic")
      //total de instalaciones q tienen algun estado de avalcitma
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_citma_nomenclador.estado = 'Vencida' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xavalcitma")
      //total de instalaciones q tienen algun estado de avalapci
      ->selectRaw("count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_apci_nomenclador.estado = 'Vencida' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') then 1 end) as total_inst_xavalapci")
      //calculo % Lic. Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtlic_sanitaria_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtlic_sanitaria_nomenclador.estado = 'Retirada' or mtlic_sanitaria_nomenclador.estado = 'Vigente' or mtlic_sanitaria_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoLicVigente")
      //calculo % Aval Citma Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_citma_nomenclador.estado = 'Vencida' or mtaval_citma_nomenclador.estado = 'Vigente' or mtaval_citma_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoAvalCitmaVigente")
      //calculo % Aval Apci Vigente
      ->selectRaw("round(count(case when (mtinstalacion.entidad_id = mtentidad.id and mtaval_citma_nomenclador.estado = 'Vigente') then 1 end) / count(case when mtinstalacion.entidad_id = mtentidad.id and (mtaval_apci_nomenclador.estado = 'Vencida' or mtaval_apci_nomenclador.estado = 'Vigente' or mtaval_apci_nomenclador.estado = 'En trámite') then 1 end) * 100,0) as calculoXcientoAvalApciVigente")

      ->where('mes', $request['mes'])
      ->where('anno', $request['anno'])
      ->where('mtentidad.nombre', 'Cubasol')
      ->where('mttipoinst.nombre', 'Instalacion Extra Hotelera')
      ->groupBy('entidad')
      ->get();

    $ultimaFila = $this->ultimaFila($indicadores, 'Total extrahotelera');
    if (!isset($indicadores) || $indicadores == null)
      return response()->json(['alert' => true, 'data' => null, 'msg' => 'No hay indicadores que mostrar']);
    return $ultimaFila;
  }

  /** Dashboard */
  public function getTotalLicenciasXestadoYear($request)
  {
    $result = [];
    $query = DB::table('mtindicadores_lineamiento')
      ->join('mtlic_sanitaria_nomenclador', 'mtindicadores_lineamiento.licsanitaria_id', '=', 'mtlic_sanitaria_nomenclador.id')
      ->join('mtaval_citma_nomenclador', 'mtindicadores_lineamiento.avalcitma_id', '=', 'mtaval_citma_nomenclador.id')
      ->join('mtaval_apci_nomenclador', 'mtindicadores_lineamiento.avalapci_id', '=', 'mtaval_apci_nomenclador.id')

      ->selectRaw("anno as name")
      ->selectRaw("count(case when mtlic_sanitaria_nomenclador.estado = 'Retirada' then 1 end) as lic_vencida")
      ->selectRaw("count(case when mtaval_citma_nomenclador.estado = 'Vencida' then 1 end) as avalcitma_vencida")
      ->selectRaw("count(case when mtaval_apci_nomenclador.estado = 'Vencida' then 1 end) as avalapci_vencida")

      ->groupBy('anno')
      ->where('instalacion_id', $request['instalacion_id'])
      ->get();

    $length = $query->count();
    for ($i = 0; $i < $length; $i++) {
      $obj[] = new stdClass();
      $obj[$i]->name = '';
      $obj[$i]->data = 0;
      foreach ($query as $element) {
        $obj[$i]->name = $element->name;
        $obj[$i]->data = $element->lic_vencida;
      }
      array_push($result, $obj[$i]);
    }
    return $result;
  }
}
