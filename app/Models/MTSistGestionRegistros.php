<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MTSistGestionRegistros
 * @package App\Models
 * @version Jule 23, 2021, 4:27 pm CDT
 *
 * @property string estado
 * @property integer tipoSistGest_id
 * @property integer operadora_id
 * @property date diagnostico_FI
 * @property date diagnostico_FC
 * @property date formacion_FI
 * @property date formacion_FC
 * @property date disenno_FI
 * @property date disenno_FC
 * @property date implementacion_FI
 * @property date implementacion_FC
 * @property json auditoria_F
 * @property json direccion_F
 * @property string mejora
 * @property date aval_entidad
 * @property boolean inclusion
 * @property date conciliacion_demanda
 * @property date ratificacion_demanda
 * @property date firma_contrato
 * @property date fase1_auditoriaI
 * @property date fase1_auditoriaC
 * @property date fase2_auditoriaI
 * @property date fase2_auditoriaC
 * @property date auditoria_adicionalI
 * @property date auditoria_adicionalC
 * @property string certificado_otorgado
 * @property date certificado_otorgadofecha
 * @property string alcance_certificacion
 * @property date validez_hasta
 * @property date certificado_denegado
 * @property string motivo_denegacion
 * @property json auditorias_seg_anual
 * @property date auditorias_seg_extraordinario
 * @property date cancelacion_certificado
 * @property string motivo_denegacion2
 * @property date aval_entidadR
 * @property boolean inclusionR
 * @property date conciliacion_demandaR
 * @property date ratificacion_demandaR
 * @property date firma_contratoR
 * @property date fase1_auditoriaIR
 * @property date fase1_auditoriaCR
 * @property date fase2_auditoriaIR
 * @property date fase2_auditoriaCR
 * @property date auditoria_adicionalIR
 * @property date auditoria_adicionalCR
 * @property string certificado_otorgadoR
 * @property date certificado_otorgadofechaR
 * @property string alcance_certificacionR
 * @property date validez_hastaR
 * @property date certificado_denegadoR
 * @property string motivo_denegacionR
 * @property json auditorias_seg_anualR
 * @property date auditorias_seg_extraordinarioR
 * @property date cancelacion_certificadoR
 * @property string motivo_denegacion2R
 * @property integer demanda_servicio_id
 * @property string alcance
 * @property integer cant_trabajadores
 * @property boolean gestionado
 * @property date fecha_auditoria
 * @property boolean conciliacion
 * @property date fecha_conciliacion
 * @property string estado_demanda

 */

class MTSistGestionRegistros extends Model
{
  public $table = 'mtsistgestionregistros';
  public $model = 'MTSistGestionRegistros';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'instalacion_id',
    'estado',
    'tipoSistGest_id',
    'operadora_id',
    'diagnostico_FI',
    'diagnostico_FC',
    'formacion_FI',
    'formacion_FC',
    'disenno_FI',
    'disenno_FC',
    'implementacion_FI',
    'implementacion_FC',
    'auditoria_F',
    'direccion_F',
    'mejora',
    'aval_entidad',
    'inclusion',
    'conciliacion_demanda',
    'ratificacion_demanda',
    'firma_contrato',
    'fase1_auditoriaI',
    'fase1_auditoriaC',
    'fase2_auditoriaI',
    'fase2_auditoriaC',
    'auditoria_adicionalI',
    'auditoria_adicionalC',
    'certificado_otorgado',
    'certificado_otorgadofecha',
    'alcance_certificacion',
    'validez_hasta',
    'certificado_denegado',
    'motivo_denegacion',
    'auditorias_seg_anual',
    'auditorias_seg_extraordinario',
    'cancelacion_certificado',
    'motivo_denegacion2',
    'aval_entidadR',
    'inclusionR',
    'conciliacion_demandaR',
    'ratificacion_demandaR',
    'firma_contratoR',
    'fase1_auditoriaIR',
    'fase1_auditoriaCR',
    'fase2_auditoriaIR',
    'fase2_auditoriaCR',
    'auditoria_adicionalIR',
    'auditoria_adicionalCR',
    'certificado_otorgadoR',
    'certificado_otorgadofechaR',
    'alcance_certificacionR',
    'validez_hastaR',
    'certificado_denegadoR',
    'motivo_denegacionR',
    'auditorias_seg_anualR',
    'auditorias_seg_extraordinarioR',
    'cancelacion_certificadoR',
    'motivo_denegacion2R',
    'demanda_servicio_id',
    'alcance',
    'cant_trabajadores',
    'gestionado',
    'fecha_auditoria',
    'conciliacion',
    'fecha_conciliacion',
    'estado_demanda'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'instalacion_id' => 'integer',
    'estado' => 'string',
    'tipoSistGest_id' => 'integer',
    'operadora_id' => 'integer',
    'diagnostico_FI' => 'date',
    'diagnostico_FC' => 'date',
    'formacion_FI' => 'date',
    'formacion_FC' => 'date',
    'disenno_FI' => 'date',
    'disenno_FC' => 'date',
    'implementacion_FI' => 'date',
    'implementacion_FC' => 'date',
    'auditoria_F' => 'json',
    'direccion_F' => 'json',
    'mejora' => 'string',
    'aval_entidad' => 'date',
    'inclusion' => 'boolean',
    'conciliacion_demanda' => 'date',
    'ratificacion_demanda' => 'date',
    'firma_contrato' => 'date',
    'fase1_auditoriaI' => 'date',
    'fase1_auditoriaC' => 'date',
    'fase2_auditoriaI' => 'date',
    'fase2_auditoriaC' => 'date',
    'auditoria_adicionalI' => 'date',
    'auditoria_adicionalC' => 'date',
    'certificado_otorgado' => 'string',
    'certificado_otorgadofecha' => 'string',
    'alcance_certificacion' => 'string',
    'validez_hasta' => 'date',
    'certificado_denegado' => 'date',
    'motivo_denegacion' => 'string',
    'auditorias_seg_anual' => 'json',
    'auditorias_seg_extraordinario' => 'date',
    'cancelacion_certificado' => 'date',
    'motivo_denegacion2' => 'string',
    'aval_entidadR' => 'date',
    'inclusionR' => 'boolean',
    'conciliacion_demandaR' => 'date',
    'ratificacion_demandaR' => 'date',
    'firma_contratoR' => 'date',
    'fase1_auditoriaIR' => 'date',
    'fase1_auditoriaCR' => 'date',
    'fase2_auditoriaIR' => 'date',
    'fase2_auditoriaCR' => 'date',
    'auditoria_adicionalIR' => 'date',
    'auditoria_adicionalCR' => 'date',
    'certificado_otorgadoR' => 'string',
    'certificado_otorgadofechaR' => 'string',
    'alcance_certificacionR' => 'string',
    'validez_hastaR' => 'date',
    'certificado_denegadoR' => 'date',
    'motivo_denegacionR' => 'string',
    'auditorias_seg_anualR' => 'json',
    'auditorias_seg_extraordinarioR' => 'date',
    'cancelacion_certificadoR' => 'date',
    'motivo_denegacion2R' => 'string',
    'demanda_servicio_id' => 'integer',
    'alcance' => 'string',
    'cant_trabajadores' => 'integer',
    'gestionado' => 'boolean',
    'fecha_auditoria' => 'date',
    'conciliacion' => 'boolean',
    'fecha_conciliacion' => 'date',
    'estado_demanda' => 'string'
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/

  public function mtinstalacions()
  {
    return $this->belongsTo(\App\Models\MTInstalacion::class, 'instalacion_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/
  public function mttipossistgests()
  {
    return $this->belongsTo(MTSistGestionTiposSistGestion::class, 'tipoSistGest_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/
  public function mtoperadoras()
  {
    return $this->belongsTo(MTSistGestionOperadora::class, 'operadora_id');
  }
}
